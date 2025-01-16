<?php

namespace Core\Api;

use Core\Providers\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(RateLimiter::class);
        $this->app->singleton(ApiVersionManager::class);
    }

    public function boot(): void
    {
        $this->registerMiddleware();
        $this->registerVersions();
    }

    private function registerMiddleware(): void
    {
        $this->app->router->middleware('api', [
            \App\Middleware\Api\ForceJson::class,
            \App\Middleware\Api\RateLimit::class,
            \App\Middleware\Api\Authenticate::class,
        ]);
    }

    private function registerVersions(): void
    {
        $this->app->get(ApiVersionManager::class)->register([
            'v1' => [
                'prefix' => 'v1',
                'middleware' => [],
            ],
            'v2' => [
                'prefix' => 'v2',
                'middleware' => [],
            ],
        ]);
    }
} 