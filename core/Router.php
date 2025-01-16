<?php

namespace Core;

class Router
{
    private array $routes = [];
    private Request $request;
    private Response $response;
    private array $middlewares = [];
    private string $prefix = '';

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get(string $path, $callback, array $middlewares = []): void
    {
        $this->addRoute('GET', $path, $callback, $middlewares);
    }

    public function post(string $path, $callback, array $middlewares = []): void
    {
        $this->addRoute('POST', $path, $callback, $middlewares);
    }

    private function addRoute(string $method, string $path, $callback, array $middlewares): void
    {
        // Convert route parameters like /users/{id} to regex pattern
        $pattern = preg_replace('/\{([a-zA-Z]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = "#^" . $pattern . "$#";

        $this->routes[$method][$pattern] = [
            'callback' => $callback,
            'middlewares' => $middlewares
        ];
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $route = $this->findRoute($method, $path);

        if (!$route) {
            throw new NotFoundException();
        }

        // Execute middleware stack
        foreach ($route['middlewares'] as $middleware) {
            $middlewareInstance = new $middleware();
            $middlewareInstance->handle($this->request, $this->response);
        }

        if (is_array($route['callback'])) {
            [$controller, $action] = $route['callback'];
            $controller = new $controller();
            return $controller->$action($this->request, $this->response, $route['params'] ?? []);
        }

        return call_user_func($route['callback'], $this->request, $this->response);
    }

    private function findRoute(string $method, string $path): ?array
    {
        if (!isset($this->routes[$method])) {
            return null;
        }

        foreach ($this->routes[$method] as $pattern => $route) {
            if (preg_match($pattern, $path, $matches)) {
                // Remove numeric keys from matches
                $params = array_filter($matches, fn($key) => !is_numeric($key), ARRAY_FILTER_USE_KEY);
                return array_merge($route, ['params' => $params]);
            }
        }

        return null;
    }

    public function apiGroup(string $prefix, array $middleware, \Closure $callback): void
    {
        $originalPrefix = $this->prefix;
        $originalMiddleware = $this->middleware;

        $this->prefix = $prefix;
        $this->middleware = array_merge($this->middleware, $middleware);

        $callback($this);

        $this->prefix = $originalPrefix;
        $this->middleware = $originalMiddleware;
    }
} 