<?php

declare(strict_types=1);

use Hexlet\Code\Controller\AddUrlsController;
use Hexlet\Code\Controller\CheckUrlsController;
use Hexlet\Code\Controller\HomeController;
use Hexlet\Code\Controller\ListUrlsController;
use Hexlet\Code\Controller\ShowUrlsController;
use Hexlet\Code\Repository\DbUrlRepository;
use Hexlet\Code\Repository\UrlRepositoryInterface;
use Hexlet\Code\Service\DbConnection;
use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Flash\Messages;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Valitron\Validator;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(dirname(__DIR__));
$dotenv->safeLoad();

Validator::lang('ru');

$container = new Container();
$container->set(Twig::class, fn() => Twig::create('../templates'));
$container->set(Messages::class, fn() => new Messages());
$container->set(PDO::class, fn() => DbConnection::get()->connect());
$container->set(UrlRepositoryInterface::class, DI\autowire(DbUrlRepository::class));

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