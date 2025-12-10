# Laravel Http Logger

[![Latest Version on Packagist](https://img.shields.io/packagist/v/keerill/laravel-http-client-logger.svg?style=flat-square)](https://packagist.org/packages/keerill/laravel-http-client-logger)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/keerill/laravel-http-client-logger/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/keerill/laravel-http-client-logger/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/keerill/laravel-http-client-logger/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/keerill/laravel-http-client-logger/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/keerill/laravel-http-client-logger.svg?style=flat-square)](https://packagist.org/packages/keerill/laravel-http-client-logger)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require keerill/laravel-http-client-logger
```

## Usage

```php
use Psr\Log\LoggerInterface;

/** @var LoggerInterface $logger */
$logger = logger();

$response = Http::baseUrl('http://microservice/api/v1')
        ->withMiddleware(new HttpLoggerMiddleware($logger, new ContextFormatter()))
        ->post('/healthcheck', ['test' => 'message'])
        ->json();

// ...
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Kirill Popkov](https://github.com/popkovkirill)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
