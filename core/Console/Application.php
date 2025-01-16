<?php

namespace Core\Console;

use Core\Console\Commands\MigrateCommand;
use Core\Console\Commands\MigrateRollbackCommand;
use Core\Console\Commands\MakeMigrationCommand;
use Core\Console\Commands\MakeControllerCommand;
use Core\Console\Commands\MakeModelCommand;

class Application
{
    private array $commands = [];
    private array $defaultCommands = [
        'migrate' => MigrateCommand::class,
        'migrate:rollback' => MigrateRollbackCommand::class,
        'make:migration' => MakeMigrationCommand::class,
        'make:controller' => MakeControllerCommand::class,
        'make:model' => MakeModelCommand::class,
    ];

    public function __construct()
    {
        $this->registerDefaultCommands();
    }

    public function run(array $argv): int
    {
        $command = $argv[1] ?? 'list';
        $params = array_slice($argv, 2);

        if (!isset($this->commands[$command])) {
            echo "Command not found: {$command}\n";
            return 1;
        }

        try {
            $instance = new $this->commands[$command](app());
            $instance->handle($params);
            return 0;
        } catch (\Exception $e) {
            echo "Error: {$e->getMessage()}\n";
            return 1;
        }
    }

    private function registerDefaultCommands(): void
    {
        foreach ($this->defaultCommands as $name => $class) {
            $this->commands[$name] = $class;
        }
    }
} 