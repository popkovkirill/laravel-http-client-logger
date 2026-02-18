<?php

namespace Keerill\HttpLogger;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\Create;
use GuzzleHttp\Promise\PromiseInterface;
use Keerill\HttpLogger\Formatters\FormatterInterface;
use Keerill\HttpLogger\Resolvers\LogLevelResolver;
use Keerill\HttpLogger\Resolvers\LogLevelResolverInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

final readonly class HttpLoggerMiddleware
{
    public function __construct(
        private LoggerInterface $logger,
        private FormatterInterface $formatter,
        private array $context = [],
        private LogLevelResolverInterface $logLevelResolver = new LogLevelResolver(),
    ) {
    }

    private function logging(
        RequestInterface $request,
        ?ResponseInterface $response = null
    ): void {
        $message = $this->formatter->getMessage($request, $response);
        $context = array_merge($this->context, $this->formatter->getContext($request, $response));

        $logLevel = $this->logLevelResolver
            ->resolve($request, $response);

        $response?->getBody()->rewind();

        $response?->getBody()->rewind();

        $this->logger
            ->log($logLevel, $message, $context);
    }

    private function onSuccess(RequestInterface $request): callable
    {
        return function (ResponseInterface $response) use ($request) {
            $this->logging($request, $response);

            return $response;
        };
    }

    private function onFailure(RequestInterface $request): callable
    {
        return function ($exception) use ($request) {
            $response = $exception instanceof RequestException ? $exception->getResponse() : null;
            $this->logging($request, $response);

            throw Create::rejectionFor($exception);
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
