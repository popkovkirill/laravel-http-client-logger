<?php

use Keerill\HttpLogger\Formatters\HeadersFormatter;

it('can formatter headers', function (
    string $header,
    string $value,
    ?string $expected,
    array $only = ['*'],
    array $except = []
) {
    $headersFormatter = (new HeadersFormatter())
        ->only($only)
        ->except($except);

    $headerFormatted = $headersFormatter->format([$header => [$value]]);

    expect($headerFormatted[$header] ?? null)
        ->toBe($expected);
})->with([
    ['authorization', 'token', '****'],
    ['set-cookie', 'coookies', '****'],
    ['cookie', 'coookies', '****'],
    ['visible-header', 'value', 'value', ['visible-header']],
    ['not-visible-header', 'value', null, ['visible-header']],
    ['excepted-visible-header', 'value', null, ['*'], ['excepted-visible-header']],
    ['header', 'value', 'value'],
]);
