<?php

declare(strict_types=1);

namespace Hexlet\Code\Service;

use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class UrlChecker
{
    public function __construct(private Client $httpClient, private Document $document)
    {
    }

    /**
     * @throws GuzzleException
     * @throws InvalidSelectorException
     */
    public function check(string $url): array
    {
        $response = $this->httpClient->request('GET', $url, ['timeout' => 3]);

        $data = $this->extractDataFromContent($response->getBody()->getContents());

        return array_merge(
            ['status_code' => $response->getStatusCode()],
            $data
        );
    }

    /**
     * @throws InvalidSelectorException
     */
    private function extractDataFromContent(string $content): array
    {
        $this->document->loadHtml($content);
        $h1 = $this->document->first('h1');
        $title = $this->document->first('title');
        $description = $this->document->first('meta[name="description"]');
        return [
            'h1'          => optional($h1)->text(),
            'title'       => optional($title)->text(),
            'description' => optional($description)->getAttribute('content'),
        ];
    }
}