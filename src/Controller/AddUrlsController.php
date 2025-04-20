<?php

declare(strict_types=1);

namespace Hexlet\Code\Controller;

use DateTimeZone;
use Hexlet\Code\Repository\UrlRepositoryInterface;
use Hexlet\Code\Service\UrlNormalizer;
use Hexlet\Code\Service\UrlValidator;
use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages;
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AddUrlsController
{
    public function __construct(
        private Twig $twig,
        private Messages $flash,
        private RouteCollectorInterface $routeCollector,
        private UrlRepositoryInterface $urlRepository,
        private UrlNormalizer $urlNormalizer,
        private UrlValidator $urlValidator
    ) {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws \Exception
     */
    public function __invoke(ServerRequest $request, Response $response): ResponseInterface
    {
        $url = $request->getParsedBodyParam('url');
        $url['name'] = $this->urlNormalizer->normalize($url['name']);

        if ($this->urlValidator->validate($url)) {
            if (!$existingUrl = $this->urlRepository->findOneByName($url['name'])) {
                $timezone = new DateTimeZone('Europe/Moscow');
                $url['created_at'] = (new DateTimeImmutable('now', $timezone))->format('c');

                $id = $this->urlRepository->add($url);

                $this->flash->addMessage('success', 'Страница успешно добавлена');
            } else {
                $id = $existingUrl['id'];

                $this->flash->addMessage('success', 'Страница уже существует');
            }

            return $response->withRedirect(
                $this->routeCollector->getRouteParser()->urlFor('url', ['id' => $id])
            );
        }

        $params = [
            'url'     => $url,
            'errors'  => $this->urlValidator->getErrors(),
            'flashes' => $this->flash->getMessages(),
        ];

        return $this->twig->render($response->withStatus(403), 'app/home.html.twig', $params);
    }
}