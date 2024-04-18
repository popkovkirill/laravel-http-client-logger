<?php

namespace Keerill\HttpLogger\Parsers;

class MultipartFormDataParser
{
    public function parse(string $body, string $boundary): array
    {
        if (! $boundary) {
            return [];
        }

        $params = [];

        $boundaryPattern = sprintf("/(\r\n)?--%s\s*?(\r\n)?/", preg_quote($boundary, '/'));
        $parts = preg_split($boundaryPattern, $body);

        if (! is_array($parts)) {
            return [];
        }

        array_shift($parts);
        array_pop($parts);

        foreach ($parts as $part) {
            $partMessage = explode("\r\n\r\n", $part, 2);

            if (count($partMessage) < 2) {
                // Missing headers are not a fault according to RFC 2046,
                // but we can't process this part without it.
                continue;
            }

            [$headers, $body] = $partMessage;

            $headers = $this->parseHeaders($headers);
            $disposition = $headers['content-disposition'] ?? null;

            if ($disposition === null || ! $disposition->isFormData()) {
                continue;
            }

            $name = $disposition->getKeyValue('name')
                ?: 'unknown';

            if ($filename = $disposition->getKeyValue('filename')) {
                $params[$name] = "$filename (file)";

                continue;
            }

            $params[$name] = trim($body);
        }

        return $params;
    }

    /**
     * @return HeaderLine[]
     */
    private function parseHeaders(string $headerData): array
    {
        $rawHeaders = explode("\r\n", $headerData);
        $headers = [];

        foreach ($rawHeaders as $rawHeader) {
            $header = new HeaderLine($rawHeader);

            $name = strtolower($header->getName());
            $headers[$name] = $header;
        }

        return $headers;
    }
}
