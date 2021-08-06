# Hirefire middleware for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/adepem/laravel-hirefire.svg?style=flat-square)](https://packagist.org/packages/adepem/laravel-hirefire)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/adepem/laravel-hirefire/Laravel?label=tests)](https://github.com/adepem/laravel-hirefire/actions?query=workflow%3ALaravel+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/adepem/laravel-hirefire.svg?style=flat-square)](https://packagist.org/packages/adepem/laravel-hirefire)

## Installation

You can install the package via composer:

```bash
composer require adepem/laravel-hirefire
```

## Usage

Register the middleware in your middleware stack in `app/Http/Kernel.php`:

```php
class Kernel extends HttpKernel
{
    protected $middleware = [
        \Adepem\HirefireMiddleware\HirefireMiddleware::class,
        // ...
    ];
    
    // ...
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [sirdharma](https://github.com/sirdharma)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
