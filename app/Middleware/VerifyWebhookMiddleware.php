<?php

namespace App\Middleware;

use Core\Middleware\MiddlewareInterface;
use Core\Http\Request;
use Core\Support\Facades\Config;

class VerifyWebhookMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next)
    {
        if (!Config::get('webhooks.enabled')) {
            return response()->json(['error' => 'Webhooks are disabled'], 404);
        }

        $provider = $request->route('provider');
        if (!Config::get("webhooks.handlers.{$provider}")) {
            return response()->json(['error' => 'Invalid webhook provider'], 400);
        }

        if (!$request->header('X-Webhook-Event')) {
            return response()->json(['error' => 'Missing webhook event header'], 400);
        }

        return $next($request);
    }
} 