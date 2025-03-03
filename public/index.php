<?php

use Slim\Factory\AppFactory;
use DI\Container;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();

$container->set(Twig::class, function () {
    return Twig::create(__DIR__ . '/../templates', ['cache' => false]);
});

$app = AppFactory::createFromContainer($container);

// $app->add(TwigMiddleware::create($app, $container->get(Twig::class)));

$app->addErrorMiddleware(true, true, true);

// Обработчик главной страницы
$app->get('/', function (Request $request, Response $response, $args) {
    return $this->get(Twig::class)->render($response, 'main.html.twig');
})->setName('main');

// $app->get('/', function () {
//     return 'index.html.twig';
// });

$app->run();
