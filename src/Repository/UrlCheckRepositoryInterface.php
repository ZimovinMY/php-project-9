<?php

declare(strict_types=1);

namespace Hexlet\Code\Repository;

interface UrlCheckRepositoryInterface
{
    public function add(array $check): string;

    public function get(string $urlId): array;
}