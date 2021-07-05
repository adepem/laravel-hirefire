<?php

namespace Adepem\HirefireMiddleware;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class HirefireMiddlewareServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-hirefire')
            ->hasConfigFile();
    }

    public function boot()
    {
        config()->set('logging.channels.laravel-hirefire', [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'with' => [
                'stream' => 'php://stdout',
            ],
            'formatter' => LineFormatter::class,
            'formatter_with' => [
                "format" => "%message%\n",
            ],
        ]);

        return parent::boot();
    }
}
