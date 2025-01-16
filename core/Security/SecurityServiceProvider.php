<?php

namespace Core\Security;

use Core\Providers\ServiceProvider;

class SecurityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CSRF::class);
        $this->app->singleton(XSS::class);
        $this->app->singleton(Security::class);
    }

    public function boot(): void
    {
        $security = $this->app->get(Security::class);
        $security->applySecurityHeaders();
    }
} 