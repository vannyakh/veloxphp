<?php

namespace Core\Console\Commands;

class GenerateApiDocsCommand
{
    public function handle(): void
    {
        $routes = app()->router->getRoutes();
        $docs = [];

        foreach ($routes as $route) {
            if (strpos($route['path'], '/api/') === 0) {
                $docs[] = $this->generateRouteDoc($route);
            }
        }

        $this->saveDocumentation($docs);
    }

    protected function generateRouteDoc(array $route): array
    {
        return [
            'path' => $route['path'],
            'method' => $route['method'],
            'description' => $this->getRouteDescription($route),
            'parameters' => $this->getRouteParameters($route),
            'responses' => $this->getRouteResponses($route)
        ];
    }

    protected function saveDocumentation(array $docs): void
    {
        $path = public_path('docs/api.json');
        file_put_contents($path, json_encode($docs, JSON_PRETTY_PRINT));
    }
} 