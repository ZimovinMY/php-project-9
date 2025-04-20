<?php

declare(strict_types=1);

namespace Hexlet\Code\Repository;

use Hexlet\Code\Exception\UrlNotFoundException;
interface UrlRepositoryInterface
{
    public function add(array $url): string;

    /**
     * @throws UrlNotFoundException
     */
    public function getOne(string $id): array;

    public function get(): array;

    public function findOneByName(string $name): ?array;
}