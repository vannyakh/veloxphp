<?php

use App\Controllers\Api\UserController;
use App\Middleware\ApiAuth;
use App\Controllers\WebhookController;
use App\Middleware\VerifyWebhookMiddleware;

/** @var \Core\Router $router */

$router->apiGroup('/api', [ApiAuth::class], function($router) {
    $router->get('/users', [UserController::class, 'index']);
    $router->post('/users', [UserController::class, 'store']);
    $router->get('/users/{id}', [UserController::class, 'show']);
    $router->put('/users/{id}', [UserController::class, 'update']);
    $router->delete('/users/{id}', [UserController::class, 'destroy']);
});

$router->group('/webhooks', ['middleware' => [VerifyWebhookMiddleware::class]], function($router) {
    $router->post('/{provider}', [WebhookController::class, 'handle']);
}); 