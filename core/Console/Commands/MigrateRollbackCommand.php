<?php

namespace Core\Console\Commands;

class MigrateRollbackCommand
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function handle(array $params = []): void
    {
        $batch = $this->getLastBatch();
        if (!$batch) {
            echo "Nothing to rollback.\n";
            return;
        }

        $migrations = $this->getMigrationsForBatch($batch);
        
        foreach ($migrations as $migration) {
            $this->rollbackMigration($migration);
        }
    }

    private function getLastBatch(): int
    {
        $result = $this->db->table('migrations')
            ->select(['batch'])
            ->orderBy('batch', 'DESC')
            ->first();

        return $result ? (int) $result['batch'] : 0;
    }

    private function getMigrationsForBatch(int $batch): array
    {
        return $this->db->table('migrations')
            ->where('batch', '=', $batch)
            ->orderBy('id', 'DESC')
            ->get();
    }

    private function rollbackMigration(array $migration): void
    {
        $file = app()->rootPath . '/database/migrations/' . $migration['migration'] . '.php';
        
        if (!file_exists($file)) {
            throw new \RuntimeException("Migration file not found: {$file}");
        }

        require_once $file;
        
        $class = $this->getMigrationClass($migration['migration']);
        $instance = new $class($this->db);

        $this->db->transaction(function() use ($instance, $migration) {
            $instance->down();
            
            $this->db->table('migrations')
                ->where('id', '=', $migration['id'])
                ->delete();
        });

        echo "Rolled back: {$migration['migration']}\n";
    }
} 