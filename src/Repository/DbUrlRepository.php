<?php

declare(strict_types=1);

namespace Hexlet\Code\Repository;

use Hexlet\Code\Exception\UrlNotFoundException;
use PDO;

class DbUrlRepository implements UrlRepositoryInterface
{
    public function __construct(private PDO $pdoConnection)
    {
    }

    public function add(array $url): string
    {
        $sql = 'INSERT INTO urls (name, created_at) VALUES(:name, :created_at)';

        $stmt = $this->pdoConnection->prepare($sql);

        foreach ($url as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();

        return $this->pdoConnection->lastInsertId('urls_id_seq');
    }

    /**
     * @throws UrlNotFoundException
     */
    public function getOne(string $id): array
    {
        $sql = 'SELECT * FROM urls WHERE id = :id';

        $stmt = $this->pdoConnection->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        if (!$result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            throw new UrlNotFoundException();
        }
        return $result;
    }

    public function get(): array
    {
        $sql = 'SELECT * FROM urls ORDER BY created_at DESC;';

        $stmt = $this->pdoConnection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findOneByName(string $name): ?array
    {
        $sql = 'SELECT * FROM urls WHERE name = :name';

        $stmt = $this->pdoConnection->prepare($sql);
        $stmt->bindValue(':name', $name);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}