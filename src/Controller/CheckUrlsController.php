<?php

declare(strict_types=1);

namespace Hexlet\Code\Controller;

use DateTimeZone;
use Hexlet\Code\Exception\UrlNotFoundException;
use Hexlet\Code\Repository\UrlCheckRepositoryInterface;
use Hexlet\Code\Repository\UrlRepositoryInterface;
use Hexlet\Code\Service\UrlChecker;
use DateTimeImmutable;
use Exception;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages;
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CheckUrlsController
{
    public function __construct(
        private RouteCollectorInterface $routeCollector,
        private Twig $twig,
        private Messages $flash,
        private UrlRepositoryInterface $urlRepository,
        private UrlCheckRepositoryInterface $urlCheckRepository,
        private UrlChecker $urlChecker
    ) {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws GuzzleException
     * @throws LoaderError
     */
    public function __invoke(ServerRequest $request, Response $response, array $args): ResponseInterface
    {
        try {
            $urlId = $args['id'];
            $url = $this->urlRepository->getOne($urlId);

            try {
                $checkResult = $this->urlChecker->check($url['name']);

                $check = $this->buildNewCheck($urlId, $checkResult);

                $this->urlCheckRepository->add($check);

                $this->flash->addMessage('success', 'Страница успешно проверена');
            } catch (ConnectException) {
                $this->flash->addMessage('error', 'Произошла ошибка при проверке, не удалось подключиться');
            } catch (RequestException) {
                $this->flash->addMessage('error', 'Произошла ошибка при проверке. Ошибка при выполнении запроса');
            }

            return $response->withRedirect($this->routeCollector->getRouteParser()->urlFor('url', ['id' => $urlId]));
        } catch (UrlNotFoundException) {
            return $this->twig->render($response->withStatus(404), 'app/404.html.twig');
        } catch (Exception $e) {
            $this->flash->addMessageNow('error', $e->getMessage());
            $data = ['flashes' => $this->flash->getMessages()];

            return $this->twig->render($response->withStatus(500), 'app/500.html.twig', $data);
        }
    }

    /**
     * @throws Exception
     */
    private function buildNewCheck(string $urlId, array $checkResult): array
    {
        $timezone = new DateTimeZone('Europe/Moscow');
        return [
            'url_id'      => $urlId,
            'created_at' => (new DateTimeImmutable('now', $timezone))->format('c'),
            'status_code' => $checkResult['status_code'],
            'h1'          => mb_substr($checkResult['h1'] ?? '', 0, 255),
            'title'       => mb_substr($checkResult['title'] ?? '', 0, 255),
            'description' => mb_substr($checkResult['description'] ?? '', 0, 255),
        ];
    }
}