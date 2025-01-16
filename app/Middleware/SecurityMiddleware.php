<?php

namespace App\Middleware;

use Core\Middleware\MiddlewareInterface;
use Core\Http\Request;
use Core\Security\CSRF;
use Core\Security\XSS;

class SecurityMiddleware implements MiddlewareInterface
{
    private CSRF $csrf;
    private XSS $xss;

    public function __construct(CSRF $csrf, XSS $xss)
    {
        $this->csrf = $csrf;
        $this->xss = $xss;
    }

    public function handle(Request $request, callable $next)
    {
        if ($this->shouldValidateCSRF($request)) {
            $token = $request->input('_token');
            if (!$this->csrf->validateToken($token)) {
                throw new \Exception('CSRF token validation failed');
            }
        }

        // Clean input data
        $request->setParams($this->xss->clean($request->all()));

        return $next($request);
    }

    private function shouldValidateCSRF(Request $request): bool
    {
        return in_array($request->getMethod(), ['POST', 'PUT', 'DELETE', 'PATCH'])
            && !$this->isApiRequest($request);
    }

    private function isApiRequest(Request $request): bool
    {
        return str_starts_with($request->getPath(), '/api/');
    }
} 