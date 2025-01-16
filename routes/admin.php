<?php

use App\Controllers\Admin\DashboardController;
use App\Middleware\Admin\AuthMiddleware;

/** @var \Core\Router $router */

$router->group('/admin', [AuthMiddleware::class], function($router) {
    $router->get('/', [DashboardController::class, 'index']);
    $router->get('/dashboard', [DashboardController::class, 'index']);
    
    // Add more admin routes
}); 