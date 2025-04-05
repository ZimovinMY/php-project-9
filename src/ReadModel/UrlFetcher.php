<?php

declare(strict_types=1);

namespace Hexlet\Code\ReadModel;

use PDO;

class UrlFetcher
{
    public function __construct(private PDO $pdoConnection)
    {
    }

    public function getAllDetail(): array
    {
        $sql = '
            SELECT urls.id,
               urls.name,
               checks.created_at AS last_check_date,
               checks.status_code
            FROM urls
            LEFT JOIN (
                SELECT DISTINCT ON (url_id) url_id, created_at, status_code
                FROM url_checks
                ORDER BY url_id, created_at DESC) checks
            ON urls.id = checks.url_id
            ORDER BY urls.created_at DESC;';

        $stmt = $this->pdoConnection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}