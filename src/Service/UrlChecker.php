<?php

declare(strict_types=1);

namespace Hexlet\Code\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class UrlChecker
{
    public function __construct(private Client $httpClient)
    {
    }

    /**
     * @throws GuzzleException
     */
    public function check(string $url): array
    {
        $response = $this->httpClient->request('GET', $url, ['timeout' => 3]);

        return [
            'status_code' => $response->getStatusCode(),
        ];
    }
}