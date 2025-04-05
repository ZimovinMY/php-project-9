<?php

declare(strict_types=1);

namespace Hexlet\Code\Controller;

use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Slim\Interfaces\RouteCollectorInterface;

class CheckUrlsController
{
    public function __construct(
        private RouteCollectorInterface $routeCollector
    ) {
    }

    public function __invoke(ServerRequest $request, Response $response, array $args): ResponseInterface
    {
        $id = $args['id'];

        return $response->withRedirect(
            $this->routeCollector->getRouteParser()->urlFor('url', ['id' => $id])
        );
    }
}