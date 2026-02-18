<?php

declare(strict_types=1);

namespace Keerill\HttpLogger\Resolvers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LogLevel;

final readonly class LogLevelResolver implements LogLevelResolverInterface
{
    public function __construct(
        private string $successLevel = LogLevel::INFO,
    ) {
    }

    public function resolve(RequestInterface $request, ?ResponseInterface $response = null): string
    {
        $statusCode = $response?->getStatusCode() ?: 0;

        return match (true) {
            $statusCode >= 200 && $statusCode < 300 => $this->successLevel,
            $statusCode >= 300 && $statusCode < 500 => LogLevel::WARNING,
            default => LogLevel::ERROR,
        };
    }
}
