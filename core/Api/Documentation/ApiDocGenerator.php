<?php

namespace Core\Api\Documentation;

class ApiDocGenerator
{
    private array $routes;
    private array $docs = [];

    public function generate(): array
    {
        $this->routes = app()->router->getRoutes();
        
        foreach ($this->routes as $route) {
            if ($this->isApiRoute($route)) {
                $this->docs[] = $this->generateRouteDoc($route);
            }
        }

        return $this->docs;
    }

    private function generateRouteDoc(array $route): array
    {
        $controller = $this->getController($route);
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod($route['action']);
        $docComment = $method->getDocComment();

        return [
            'path' => $route['path'],
            'method' => $route['method'],
            'description' => $this->parseDescription($docComment),
            'parameters' => $this->parseParameters($docComment),
            'responses' => $this->parseResponses($docComment),
            'headers' => $this->parseHeaders($docComment),
            'examples' => $this->parseExamples($docComment),
        ];
    }

    private function parseDescription(string $docComment): string
    {
        preg_match('/@description\s+(.+)/i', $docComment, $matches);
        return $matches[1] ?? '';
    }

    private function parseParameters(string $docComment): array
    {
        preg_match_all('/@param\s+(\w+)\s+\$(\w+)\s+(.+)/i', $docComment, $matches, PREG_SET_ORDER);
        
        return array_map(function($match) {
            return [
                'name' => $match[2],
                'type' => $match[1],
                'description' => $match[3]
            ];
        }, $matches);
    }
} 