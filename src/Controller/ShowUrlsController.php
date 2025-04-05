<?php

declare(strict_types=1);

namespace Hexlet\Code\Controller;

use Hexlet\Code\Repository\UrlRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages;
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ShowUrlsController
{
    public function __construct(
        private Twig $twig,
        private Messages $flash,
        private UrlRepositoryInterface $repository
    ) {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function __invoke(ServerRequest $request, Response $response, array $args): ResponseInterface
    {
        if (!$url = $this->repository->getOne($args['id'])) {
            return $response->withStatus(404)->write('Page not found');
        }

        $data = [
            'url'     => $url,
            'flashes' => $this->flash->getMessages(),
        ];

        return $this->twig->render($response, 'app/urls/show.html.twig', $data);
    }
}