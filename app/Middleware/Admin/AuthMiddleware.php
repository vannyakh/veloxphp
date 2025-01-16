<?php

namespace App\Middleware\Admin;

use Core\Middleware\MiddlewareInterface;
use Core\Http\Request;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect('/login');
        }

        return $next($request);
    }
} 