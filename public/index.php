<?php

declare(strict_types=1);

use Hexlet\Code\Controller\AddUrlsController;
use Hexlet\Code\Controller\CheckUrlsController;
use Hexlet\Code\Controller\HomeController;
use Hexlet\Code\Controller\ListUrlsController;
use Hexlet\Code\Controller\ShowUrlsController;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Valitron\Validator;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(dirname(__DIR__));
$dotenv->safeLoad();

Validator::lang('ru');

$container = (new ContainerBuilder())
    ->addDefinitions(__DIR__ . '/../config/container.php')
    ->build();

$app = AppFactory::createFromContainer($container);
$app->add(TwigMiddleware::createFromContainer($app, Twig::class));
$app->add(function ($request, $next) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    return $next->handle($request);
});
$app->addErrorMiddleware(true, true, true);

$container->set(RouteCollectorInterface::class, fn() => $app->getRouteCollector());

$app->get('/', HomeController::class)->setName('home');
$app->post('/urls', AddUrlsController::class)->setName('addUrl');
$app->get('/urls/{id}', ShowUrlsController::class)->setName('url');
$app->get('/urls', ListUrlsController::class)->setName('urls');
$app->post('/urls/{id}/checks', CheckUrlsController::class)->setName('checkUrl');

$app->run();