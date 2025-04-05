<?php

declare(strict_types=1);

namespace Hexlet\Code\Service;

class UrlNormalizer
{
    public function normalize(string $url): string
    {
        $parts = parse_url($url);

        return strtolower($parts['scheme'] . '://' . $parts['host']);
    }
}