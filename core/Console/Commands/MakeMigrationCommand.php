<?php

namespace Core\Console\Commands;

class MakeMigrationCommand
{
    private string $stubPath;

    public function __construct()
    {
        $this->stubPath = app()->rootPath . '/core/Console/stubs';
    }

    public function handle(array $params): void
    {
        if (empty($params[0])) {
            throw new \InvalidArgumentException('Migration name is required');
        }

        $name = $params[0];
        $table = $this->getTableName($name);
        $className = $this->getClassName($name);
        $path = $this->createMigrationFile($className, $table);

        echo "Created Migration: {$path}\n";
    }

    private function createMigrationFile(string $className, string $table): string
    {
        $date = date('Y_m_d_His');
        $filename = "{$date}_{$className}.php";
        $path = app()->rootPath . '/database/migrations/' . $filename;

        $stub = file_get_contents($this->stubPath . '/migration.stub');
        $stub = str_replace(
            ['{{className}}', '{{table}}'],
            [$className, $table],
            $stub
        );

        file_put_contents($path, $stub);
        return $path;
    }

    private function getClassName(string $name): string
    {
        $name = ucwords(str_replace(['-', '_'], ' ', $name));
        return str_replace(' ', '', $name);
    }

    private function getTableName(string $name): string
    {
        if (preg_match('/create_(\w+)_table/', $name, $matches)) {
            return $matches[1];
        }
        return '';
    }
} 