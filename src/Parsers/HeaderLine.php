<?php

namespace Keerill\HttpLogger\Parsers;

class HeaderLine
{
    private string $name;

    private string $value;

    /**
     * @var array<string, string>
     */
    private array $key_values = [];

    public function __construct(string $rawHeader)
    {
        [$name, $value] = explode(':', $rawHeader, 2);

        $this->name = $name;

        $value = explode(';', $value);
        $value = array_map('trim', $value);

        $this->value = current($value);

        foreach ($value as $raw_value_part) {
            $value_part = explode('=', $raw_value_part, 2);

            if (count($value_part) !== 2) {
                continue;
            }

            [$key, $key_value] = $value_part;

            $key = strtolower(trim($key));
            $this->key_values[$key] = trim(trim($key_value), '"\'');
        }
    }

    public function isFormData(): bool
    {
        return $this->getValue() == 'form-data';
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getKeyValue(string $key): ?string
    {
        return $this->key_values[$key] ?? null;
    }
}
