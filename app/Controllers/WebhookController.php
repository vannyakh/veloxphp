<?php

namespace App\Controllers;

use Core\Controller;
use Core\Http\Request;
use Core\Support\Facades\Config;

class WebhookController extends Controller
{
    public function handle(Request $request, string $provider)
    {
        $config = Config::get('webhooks.handlers.' . $provider);
        
        if (!$config) {
            return response()->json(['error' => 'Invalid webhook provider'], 400);
        }

        $event = $request->header('X-Webhook-Event');
        if (!isset($config['events'][$event])) {
            return response()->json(['error' => 'Unsupported webhook event'], 400);
        }

        $handlerClass = $config['events'][$event];
        $handler = new $handlerClass($config);

        return $handler->process($request);
    }
} 