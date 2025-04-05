<?php

declare(strict_types=1);

namespace Hexlet\Code\Controller;

use Hexlet\Code\ReadModel\UrlFetcher;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ListUrlsController
{
    public function __construct(private Twig $twig, private UrlFetcher $fetcher)
    {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function __invoke(ServerRequest $request, Response $response): ResponseInterface
    {
        $data = [
            'urls' => $this->fetcher->getAllDetail(),
        ];

        return $this->twig->render($response, 'app/urls/list.html.twig', $data);
    }
}