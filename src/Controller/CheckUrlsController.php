<?php

declare(strict_types=1);

namespace Hexlet\Code\Controller;

use DateTimeZone;
use Hexlet\Code\Repository\UrlCheckRepositoryInterface;
use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Slim\Interfaces\RouteCollectorInterface;

class CheckUrlsController
{
    public function __construct(
        private RouteCollectorInterface $routeCollector,
        private UrlCheckRepositoryInterface $repository
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(ServerRequest $request, Response $response, array $args): ResponseInterface
    {
        $urlId = $args['id'];
        $timezone = new DateTimeZone('Europe/Moscow');

        $check = [
            'url_id'     => $urlId,
            'created_at' => (new DateTimeImmutable('now', $timezone))->format('c'),
        ];

        $this->repository->add($check);

        return $response->withRedirect(
            $this->routeCollector->getRouteParser()->urlFor('url', ['id' => $urlId])
        );
    }
}