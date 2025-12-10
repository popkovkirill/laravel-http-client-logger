<?php

namespace Keerill\HttpLogger;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use Keerill\HttpLogger\Formatters\FormatterInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

final class HttpLoggerMiddleware
{
    public function __construct(
        protected LoggerInterface $logger,
        protected FormatterInterface $formatter,
        protected array $context = []
    ) {}

    protected function logging(RequestInterface $request, ?ResponseInterface $response = null): void
    {
        $message = $this->formatter->getMessage($request, $response);
        $context = array_merge($this->context, $this->formatter->getContext($request, $response));

        $statusCode = $response?->getStatusCode() ?: 0;
        $isSuccessful = $statusCode >= 200 && $statusCode < 300;

        $this->logger
            ->log($isSuccessful ? LogLevel::INFO : LogLevel::ERROR, $message, $context);
    }

    protected function onSuccess(RequestInterface $request): callable
    {
        return function (ResponseInterface $response) use ($request) {
            $this->logging($request, $response);

            return $response;
        };
    }

    protected function onFailure(RequestInterface $request): callable
    {
        return function (GuzzleException $exception) use ($request) {
            $response = $exception instanceof RequestException ? $exception->getResponse() : null;
            $this->logging($request, $response);
            throw $exception;
        };
    }

    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options) use ($handler): PromiseInterface {
            return $handler($request, $options)
                ->then($this->onSuccess($request), $this->onFailure($request));
        };
    }
}
