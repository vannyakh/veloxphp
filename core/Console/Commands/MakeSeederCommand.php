<?php

namespace Core\Console\Commands;

class MakeSeederCommand
{
    protected string $stubPath;

    public function __construct()
    {
        $this->stubPath = app()->rootPath . '/core/Console/stubs';
    }

    public function handle(array $args): void
    {
        if (empty($args[0])) {
            throw new \InvalidArgumentException('Seeder name is required');
        }

        $name = $args[0];
        $model = str_replace('Seeder', '', $name);
        
        $this->createSeeder($name, $model);
        echo "Created Seeder: {$name}\n";
    }

    protected function createSeeder(string $name, string $model): void
    {
        $stub = file_get_contents($this->stubPath . '/seeder.stub');
        
        $content = str_replace(
            ['{{className}}', '{{model}}'],
            [$name, $model],
            $stub
        );

        $path = app()->rootPath . '/database/seeders/';
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        file_put_contents($path . $name . '.php', $content);
    }
} 