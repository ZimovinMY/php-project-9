<?php

declare(strict_types=1);

use Hexlet\Code\Repository\DbUrlCheckRepository;
use Hexlet\Code\Repository\DbUrlRepository;
use Hexlet\Code\Repository\UrlCheckRepositoryInterface;
use Hexlet\Code\Repository\UrlRepositoryInterface;
use Hexlet\Code\Service\DbConnection;
use Slim\Flash\Messages;
use Slim\Views\Twig;

return [
    Twig::class                        => fn() => Twig::create('../templates'),
    Messages::class                    => fn() => new Messages(),
    PDO::class                         => fn() => DbConnection::get()->connect(),
    UrlRepositoryInterface::class      => DI\autowire(DbUrlRepository::class),
    UrlCheckRepositoryInterface::class => DI\autowire(DbUrlCheckRepository::class),
];