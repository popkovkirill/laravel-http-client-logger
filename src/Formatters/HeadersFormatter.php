<?php

namespace Keerill\HttpLogger\Formatters;

class HeadersFormatter
{
    private array $visible = ['*'];

    private array $except = [];

    public function __construct(
        protected array $hidden = ['authorization', 'cookie', 'set-cookie'],
        protected string $stub = '****'
    ) {
    }

    public function only(array $only = ['*']): self
    {
        $this->visible = array_map(fn (string $value) => trim(strtolower($value)), $only);

        return $this;
    }

    public function except(array $except = []): self
    {
        $this->except = array_map(fn (string $value) => trim(strtolower($value)), $except);

        return $this;
    }

    public function format(array $headers): array
    {
        $cleanedHeaders = [];

        foreach ($headers as $header => $values) {
            $header = trim(strtolower($header));

            if (! $this->isVisible($header)) {
                continue;
            }

            if (in_array($header, $this->hidden)) {
                $values[0] = $this->stub;
            }

            $cleanedHeaders[$header] = $values[0];
        }

        return $cleanedHeaders;
    }

    private function isVisible(string $header): bool
    {
        if ($this->visible == ['*'] || in_array($header, $this->visible)) {
            return ! in_array($header, $this->except);
        }

        return false;
    }
}
