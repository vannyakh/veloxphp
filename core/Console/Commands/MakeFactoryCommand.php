<?php

namespace Core\Console\Commands;

class MakeFactoryCommand
{
    protected string $stubPath;

    public function __construct()
    {
        $this->stubPath = app()->rootPath . '/core/Console/stubs';
    }

    public function handle(array $args): void
    {
        if (empty($args[0])) {
            throw new \InvalidArgumentException('Factory name is required');
        }

        $name = $args[0];
        $model = str_replace('Factory', '', $name);
        
        $this->createFactory($name, $model);
        echo "Created Factory: {$name}\n";
    }

    protected function createFactory(string $name, string $model): void
    {
        $stub = file_get_contents($this->stubPath . '/factory.stub');
        
        $content = str_replace(
            ['{{className}}', '{{model}}'],
            [$name, $model],
            $stub
        );

        $path = app()->rootPath . '/database/factories/';
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        file_put_contents($path . $name . '.php', $content);
    }
} 