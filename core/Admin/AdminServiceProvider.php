<?php

namespace Core\Admin;

use Core\Providers\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AdminManager::class);
    }

    public function boot(): void
    {
        $this->publishAssets();
        $this->loadViews();
        $this->loadConfig();
    }

    private function publishAssets(): void
    {
        $source = __DIR__ . '/../../resources/admin';
        $destination = public_path('admin');

        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
            $this->copyDirectory($source, $destination);
        }
    }

    private function loadViews(): void
    {
        $this->app->view->addNamespace('admin', __DIR__ . '/../../resources/views/admin');
    }

    private function loadConfig(): void
    {
        $this->app->config->load('admin', __DIR__ . '/../../config/admin.php');
    }
} 