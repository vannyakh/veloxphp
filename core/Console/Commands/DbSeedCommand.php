<?php

namespace Core\Console\Commands;

class DbSeedCommand
{
    public function handle(array $args): void
    {
        $class = $args[0] ?? 'DatabaseSeeder';
        $fullClass = "Database\\Seeders\\{$class}";

        if (!class_exists($fullClass)) {
            echo "\033[31mSeeder class {$class} not found\033[0m\n";
            return;
        }

        try {
            echo "\033[33mSeeding database...\033[0m\n";
            
            $seeder = new $fullClass();
            $seeder->run();

            echo "\033[32mDatabase seeding completed successfully!\033[0m\n";
        } catch (\Exception $e) {
            echo "\033[31mError seeding database: {$e->getMessage()}\033[0m\n";
        }
    }
} 