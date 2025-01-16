<?php

namespace App\Middleware\Api;

use Core\Middleware\MiddlewareInterface;
use Core\Http\Request;
use Core\Security\RateLimiter;

class ApiSecurityMiddleware implements MiddlewareInterface
{
    private RateLimiter $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, callable $next)
    {
        $key = $request->getPath() . '|' . $request->getMethod();
        
        if (!$this->limiter->attempt($key, 'api')) {
            return response()->json([
                'error' => 'Too many requests',
                'retry_after' => 60
            ], 429);
        }

        // Add security headers for API
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        return $next($request);
    }
} 