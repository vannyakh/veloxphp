<?php

namespace Core\Database;

use Core\Database;

abstract class Migration
{
    protected Database $db;
    protected Schema $schema;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->schema = new Schema($db);
    }

    abstract public function up(): void;
    abstract public function down(): void;

    protected function createTable(string $table, \Closure $callback): void
    {
        $this->schema->create($table, $callback);
    }

    protected function dropTable(string $table): void
    {
        $this->schema->drop($table);
    }

    protected function table(string $table, \Closure $callback): void
    {
        $this->schema->table($table, $callback);
    }
} 