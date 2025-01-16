<?php

namespace Core\Database;

class Blueprint
{
    private string $table;
    private array $columns = [];
    private array $indexes = [];
    private array $foreignKeys = [];

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function id(string $name = 'id'): self
    {
        return $this->addColumn([
            'name' => $name,
            'type' => 'BIGINT',
            'unsigned' => true,
            'auto_increment' => true,
            'nullable' => false
        ]);
    }

    public function string(string $name, int $length = 255): self
    {
        return $this->addColumn([
            'name' => $name,
            'type' => 'VARCHAR',
            'length' => $length
        ]);
    }

    public function text(string $name): self
    {
        return $this->addColumn([
            'name' => $name,
            'type' => 'TEXT'
        ]);
    }

    public function integer(string $name): self
    {
        return $this->addColumn([
            'name' => $name,
            'type' => 'INT'
        ]);
    }

    public function timestamp(string $name): self
    {
        return $this->addColumn([
            'name' => $name,
            'type' => 'TIMESTAMP'
        ]);
    }

    public function timestamps(): self
    {
        $this->timestamp('created_at')->nullable();
        $this->timestamp('updated_at')->nullable();
        return $this;
    }

    public function softDeletes(): self
    {
        return $this->timestamp('deleted_at')->nullable();
    }

    public function nullable(): self
    {
        $this->columns[count($this->columns) - 1]['nullable'] = true;
        return $this;
    }

    public function default($value): self
    {
        $this->columns[count($this->columns) - 1]['default'] = $value;
        return $this;
    }

    public function index(string|array $columns, string $name = null): self
    {
        $columns = (array) $columns;
        $name = $name ?? $this->createIndexName($columns);

        $this->indexes[] = [
            'type' => 'INDEX',
            'name' => $name,
            'columns' => $columns
        ];

        return $this;
    }

    public function unique(string|array $columns, string $name = null): self
    {
        $columns = (array) $columns;
        $name = $name ?? $this->createIndexName($columns, 'unique');

        $this->indexes[] = [
            'type' => 'UNIQUE',
            'name' => $name,
            'columns' => $columns
        ];

        return $this;
    }

    public function foreign(string $column): ForeignKeyDefinition
    {
        return new ForeignKeyDefinition($this, $column);
    }

    private function createIndexName(array $columns, string $type = ''): string
    {
        $index = strtolower($this->table . '_' . implode('_', $columns) . '_' . $type);
        return str_replace(['-', '.'], '_', $index);
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getIndexes(): array
    {
        return $this->indexes;
    }

    public function getForeignKeys(): array
    {
        return $this->foreignKeys;
    }

    private function addColumn(array $column): self
    {
        $this->columns[] = $column;
        return $this;
    }

    public function decimal(string $name, int $precision = 8, int $scale = 2): self
    {
        return $this->addColumn([
            'name' => $name,
            'type' => 'DECIMAL',
            'precision' => $precision,
            'scale' => $scale
        ]);
    }

    public function boolean(string $name): self
    {
        return $this->addColumn([
            'name' => $name,
            'type' => 'TINYINT',
            'length' => 1
        ]);
    }

    public function enum(string $name, array $values): self
    {
        return $this->addColumn([
            'name' => $name,
            'type' => 'ENUM',
            'values' => $values
        ]);
    }

    public function json(string $name): self
    {
        return $this->addColumn([
            'name' => $name,
            'type' => 'JSON'
        ]);
    }

    public function foreignId(string $name): self
    {
        return $this->addColumn([
            'name' => $name,
            'type' => 'BIGINT',
            'unsigned' => true
        ]);
    }

    public function rememberToken(): self
    {
        return $this->string('remember_token', 100)->nullable();
    }
} 