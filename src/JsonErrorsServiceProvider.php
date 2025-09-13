<?php

namespace Paws1234\LaravelJsonErrors;

use Illuminate\Support\ServiceProvider;
use YourVendor\LaravelJsonErrors\Http\Middleware\JsonExceptionMiddleware;

class JsonErrorsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Merge config
        $this->mergeConfigFrom(__DIR__.'/../config/json-errors.php', 'json-errors');
    }

    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__.'/../config/json-errors.php' => config_path('json-errors.php'),
        ], 'config');

        // Register middleware
        $this->app['router']->aliasMiddleware('json.errors', JsonExceptionMiddleware::class);
    }
}
