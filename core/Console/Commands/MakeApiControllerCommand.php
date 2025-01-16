<?php

namespace Core\Console\Commands;

class MakeApiControllerCommand
{
    protected string $stubPath;
    protected string $namespace = 'App\\Controllers\\Api';

    public function __construct()
    {
        $this->stubPath = app()->rootPath . '/core/Console/stubs';
    }

    public function handle(array $params): void
    {
        if (empty($params[0])) {
            throw new \InvalidArgumentException('Controller name is required');
        }

        $name = $params[0];
        $model = $params[1] ?? str_replace('Controller', '', $name);
        
        $this->createController($name, $model);
        echo "Created API Controller: {$name}\n";
    }

    protected function createController(string $name, string $model): void
    {
        $stub = file_get_contents($this->stubPath . '/api.controller.stub');
        
        $content = str_replace(
            ['{{name}}', '{{model}}', '{{namespace}}'],
            [$name, $model, $this->namespace],
            $stub
        );

        $path = app()->rootPath . '/app/Controllers/Api/';
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        file_put_contents($path . $name . '.php', $content);
    }
} 