<?php

namespace Keerill\HttpLogger\Formatters;

class HeadersFormatter
{
    public function __construct(
        protected array $hiddenHeaders = ['authorization'],
        protected string $stub = '****'
    ) {
    }

    public function format(array $headers): array
    {
        $cleanedHeaders = [];

        foreach ($headers as $header => $values) {
            $header = trim(strtolower($header));

            if (in_array($header, $this->hiddenHeaders)) {
                $values[0] = $this->stub;
            }

            $cleanedHeaders[$header] = $values[0];
        }

        return $cleanedHeaders;
    }
}
