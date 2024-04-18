<?php

namespace Keerill\HttpLogger\Formatters;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface FormatterInterface
{
    public function getMessage(RequestInterface $request, ?ResponseInterface $response = null): string;

    public function getContext(RequestInterface $request, ?ResponseInterface $response = null): array;
}
