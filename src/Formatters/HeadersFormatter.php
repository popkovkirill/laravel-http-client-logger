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
            $normalizeHeader = trim(strtolower($header));

            if (! $this->isVisible($normalizeHeader)) {
                continue;
            }

            if (in_array($normalizeHeader, $this->hidden)) {
                $values = array_map(fn () => $this->stub, $values);
            }

            $cleanedHeaders[$header] = count($values) == 1 ? $values[0] : $values;
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
