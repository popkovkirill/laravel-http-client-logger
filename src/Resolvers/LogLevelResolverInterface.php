<?php

declare(strict_types=1);

namespace Keerill\HttpLogger\Resolvers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface LogLevelResolverInterface
{
    public function resolve(RequestInterface $request, ?ResponseInterface $response = null): string;
}
