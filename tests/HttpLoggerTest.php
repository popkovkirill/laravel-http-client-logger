<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Keerill\HttpLogger\Formatters\ContextFormatter;
use Keerill\HttpLogger\HttpLoggerMiddleware;

it('test logging success request', function () {
    Log::shouldReceive('log')
        ->withArgs(function ($level, $message, $context) {
            expect($level)
                ->toBe('info')
                ->and($message)
                ->toBe('POST http://microservice/api/v1/healthcheck 200: OK')
                ->and($context['request']['content'])
                ->toBe(['test' => 'message'])
                ->and($context['response']['content'])
                ->toBe(['data' => 'OK']);

            return true;
        });

    Http::fake([
        'http://microservice/api/v1/healthcheck' => Http::response(['data' => 'OK']),
    ]);

    $response = Http::baseUrl('http://microservice/api/v1')
        ->withMiddleware(new HttpLoggerMiddleware(logger(), new ContextFormatter))
        ->post('/healthcheck', ['test' => 'message'])
        ->json();

    expect($response)
        ->toBe(['data' => 'OK']);
});

it('test logging error request', function () {
    Log::shouldReceive('log')
        ->withArgs(function ($level, $message, $context) {
            expect($level)
                ->toBe('error')
                ->and($message)
                ->toBe('POST http://microservice/api/v1/healthcheck 404: Not Found')
                ->and($context['request']['content'])
                ->toBe(['test' => 'message'])
                ->and($context['response']['content'])
                ->toBe(['data' => 'Not Found']);

            return true;
        });

    Http::fake([
        'http://microservice/api/v1/healthcheck' => Http::response(['data' => 'Not Found'], 404),
    ]);

    $response = Http::baseUrl('http://microservice/api/v1')
        ->withMiddleware(new HttpLoggerMiddleware(logger(), new ContextFormatter))
        ->post('/healthcheck', ['test' => 'message'])
        ->json();

    expect($response)
        ->toBe(['data' => 'Not Found']);
});
