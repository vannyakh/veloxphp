<?php

namespace Core\Providers;

use Core\Database\Database;
use Core\Database\Migration\Migrator;

class DatabaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Database::class, function($app) {
            return new Database(config('database.connections.' . config('database.default')));
        });

        $this->app->singleton(Migrator::class, function($app) {
            return new Migrator(
                $app->get(Database::class),
                $app->rootPath . '/database/migrations'
            );
        });
    }

    public function boot(): void
    {
        // Register custom database types or initializations
    }
} 