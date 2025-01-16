<?php

namespace Core\Console\Commands;

class MigrateCommand
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function handle(): void
    {
        // Create migrations table if it doesn't exist
        $this->createMigrationsTable();

        // Get all migration files
        $files = glob(app()->rootPath . '/database/migrations/*.php');
        $ran = $this->getRanMigrations();

        // Run pending migrations
        foreach ($files as $file) {
            $migration = $this->getMigrationName($file);
            
            if (!in_array($migration, $ran)) {
                $this->runMigration($file, $migration);
            }
        }
    }

    private function createMigrationsTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            batch INT
        )";
        
        $this->db->raw($sql);
    }

    private function getRanMigrations(): array
    {
        return $this->db->table('migrations')
            ->pluck('migration')
            ->toArray();
    }

    private function runMigration(string $file, string $migration): void
    {
        require_once $file;
        
        $class = $this->getMigrationClass($migration);
        $instance = new $class($this->db);

        $this->db->transaction(function() use ($instance, $migration) {
            $instance->up();
            
            $this->db->table('migrations')->insert([
                'migration' => $migration,
                'batch' => $this->getNextBatchNumber()
            ]);
        });

        echo "Migrated: {$migration}\n";
    }
} 