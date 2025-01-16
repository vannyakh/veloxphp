<?php

namespace Core\Database;

class Schema
{
    private Database $db;
    private array $columns = [];
    private array $indexes = [];
    private array $foreignKeys = [];

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function create(string $table, \Closure $callback): void
    {
        $blueprint = new Blueprint($table);
        $callback($blueprint);

        $sql = $this->buildCreateTableSQL($table, $blueprint);
        $this->db->raw($sql);
    }

    public function table(string $table, \Closure $callback): void
    {
        $blueprint = new Blueprint($table);
        $callback($blueprint);

        $sql = $this->buildAlterTableSQL($table, $blueprint);
        $this->db->raw($sql);
    }

    private function buildCreateTableSQL(string $table, Blueprint $blueprint): string
    {
        $columns = array_map(function($column) {
            return $this->buildColumnDefinition($column);
        }, $blueprint->getColumns());

        $indexes = array_map(function($index) {
            return $this->buildIndexDefinition($index);
        }, $blueprint->getIndexes());

        $foreignKeys = array_map(function($fk) {
            return $this->buildForeignKeyDefinition($fk);
        }, $blueprint->getForeignKeys());

        $definitions = array_merge($columns, $indexes, $foreignKeys);

        return sprintf(
            "CREATE TABLE %s (\n\t%s\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            $table,
            implode(",\n\t", $definitions)
        );
    }

    private function buildColumnDefinition(array $column): string
    {
        $sql = "{$column['name']} {$column['type']}";

        if (isset($column['length'])) {
            $sql .= "({$column['length']})";
        }

        if (isset($column['unsigned']) && $column['unsigned']) {
            $sql .= ' UNSIGNED';
        }

        if (isset($column['nullable']) && !$column['nullable']) {
            $sql .= ' NOT NULL';
        }

        if (isset($column['default'])) {
            $sql .= " DEFAULT '{$column['default']}'";
        }

        if (isset($column['auto_increment']) && $column['auto_increment']) {
            $sql .= ' AUTO_INCREMENT';
        }

        return $sql;
    }

    private function buildIndexDefinition(array $index): string
    {
        $type = strtoupper($index['type']);
        $columns = implode(', ', $index['columns']);

        if ($type === 'PRIMARY') {
            return "PRIMARY KEY ({$columns})";
        }

        return "{$type} KEY {$index['name']} ({$columns})";
    }
} 