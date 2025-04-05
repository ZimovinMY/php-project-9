<?php

declare(strict_types=1);

namespace Hexlet\Code\Repository;

interface UrlRepositoryInterface
{
    public function add(array $url): string;

    public function getOne(string $id): array;

    public function get(): array;

    public function findOneByName(string $name): ?array;
}