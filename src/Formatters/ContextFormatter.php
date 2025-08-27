<?php

namespace Keerill\HttpLogger\Formatters;

use Keerill\HttpLogger\Formatters\Messages\ContentTypeMessageFormatter;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ContextFormatter implements FormatterInterface
{
    public function __construct(
        protected HeadersFormatter $headersFormatter = new HeadersFormatter,
        protected ContentTypeMessageFormatter $messageFormatter = new ContentTypeMessageFormatter,
    ) {}

    public function getMessage(RequestInterface $request, ?ResponseInterface $response = null): string
    {
        return sprintf(
            '%s %s %s: %s',
            $request->getMethod(),
            $request->getUri(),
            $response?->getStatusCode() ?: 0,
            $response?->getReasonPhrase() ?: 'Nothing'
        );
    }

    public function getContext(RequestInterface $request, ?ResponseInterface $response = null): array
    {
        if ($response !== null) {
            return [
                'request' => [
                    'headers' => $this->formatingHeaders($request->getHeaders()),
                    'content' => $this->messageFormatter
                        ->getContent($request),
                ],

                'response' => [
                    'headers' => $this->formatingHeaders($response->getHeaders()),
                    'content' => $this->messageFormatter
                        ->getContent($response),
                ],
            ];
        }

        return [
            'request' => [
                'headers' => $this->formatingHeaders($request->getHeaders()),
                'content' => $this->messageFormatter
                    ->getContent($request),
            ],
        ];
    }

    private function formatingHeaders(array $headers): array
    {
        return $this->headersFormatter->format($headers);
    }
}
