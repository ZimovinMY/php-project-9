<?php

declare(strict_types=1);

namespace Hexlet\Code\Controller;

use Hexlet\Code\Exception\UrlNotFoundException;
use Hexlet\Code\Repository\UrlCheckRepositoryInterface;
use Hexlet\Code\Repository\UrlRepositoryInterface;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages;
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ShowUrlController
{
    public function __construct(
        private Twig $twig,
        private Messages $flash,
        private UrlRepositoryInterface $urlRepository,
        private UrlCheckRepositoryInterface $urlCheckRepository
    ) {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function __invoke(ServerRequest $request, Response $response, array $args): ResponseInterface
    {
        try {
            $url = $this->urlRepository->getOne($args['id']);
            $checks = $this->urlCheckRepository->get((string) $url['id']);

            $data = [
                'url'     => $url,
                'checks'  => $checks,
                'flashes' => $this->flash->getMessages(),
            ];

            return $this->twig->render($response, 'app/urls/show.html.twig', $data);
        } catch (UrlNotFoundException) {
            return $this->twig->render($response->withStatus(404), 'app/404.html.twig');
        } catch (Exception) {
            return $this->twig->render($response->withStatus(500), 'app/500.html.twig');
        }
    }
}