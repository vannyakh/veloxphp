<?php

namespace Core\Database;

class QueryBuilder
{
    private string $table;
    private array $wheres = [];
    private array $bindings = [];
    private array $orders = [];
    private array $columns = ['*'];
    private array $joins = [];
    private array $groups = [];
    private array $havings = [];
    private ?int $limit = null;
    private ?int $offset = null;

    // Add select columns
    public function select($columns = ['*']): self
    {
        $this->columns = is_array($columns) ? $columns : func_get_args();
        return $this;
    }

    // Add where conditions with different operators
    public function whereIn(string $column, array $values): self
    {
        $this->wheres[] = [$column, 'IN', $values];
        $this->bindings = array_merge($this->bindings, $values);
        return $this;
    }

    public function whereBetween(string $column, $min, $max): self
    {
        $this->wheres[] = [$column, 'BETWEEN', [$min, $max]];
        $this->bindings[] = $min;
        $this->bindings[] = $max;
        return $this;
    }

    public function whereNull(string $column): self
    {
        $this->wheres[] = [$column, 'IS', 'NULL'];
        return $this;
    }

    // Add different types of joins
    public function leftJoin(string $table, string $first, string $operator, string $second): self
    {
        $this->joins[] = "LEFT JOIN {$table} ON {$first} {$operator} {$second}";
        return $this;
    }

    public function rightJoin(string $table, string $first, string $operator, string $second): self
    {
        $this->joins[] = "RIGHT JOIN {$table} ON {$first} {$operator} {$second}";
        return $this;
    }

    // Add grouping and having
    public function groupBy(string ...$columns): self
    {
        $this->groups = array_merge($this->groups, $columns);
        return $this;
    }

    public function having(string $column, string $operator, $value): self
    {
        $this->havings[] = [$column, $operator, $value];
        $this->bindings[] = $value;
        return $this;
    }

    // Execute queries
    public function get()
    {
        return app()->db->query($this->toSql(), $this->getBindings())->fetchAll();
    }

    public function first()
    {
        $this->limit(1);
        return app()->db->query($this->toSql(), $this->getBindings())->fetch();
    }

    public function count(): int
    {
        $this->columns = ['COUNT(*) as count'];
        $result = $this->first();
        return (int) $result['count'];
    }

    public function exists(): bool
    {
        return $this->count() > 0;
    }

    public function toSql(): string
    {
        $sql = ["SELECT " . implode(', ', $this->columns) . " FROM {$this->table}"];

        if (!empty($this->joins)) {
            $sql[] = implode(' ', $this->joins);
        }

        if (!empty($this->wheres)) {
            $sql[] = 'WHERE ' . $this->buildWhereClause();
        }

        if (!empty($this->groups)) {
            $sql[] = 'GROUP BY ' . implode(', ', $this->groups);
        }

        if (!empty($this->havings)) {
            $sql[] = 'HAVING ' . $this->buildHavingClause();
        }

        if (!empty($this->orders)) {
            $sql[] = 'ORDER BY ' . implode(', ', $this->orders);
        }

        if ($this->limit !== null) {
            $sql[] = "LIMIT {$this->limit}";
        }

        if ($this->offset !== null) {
            $sql[] = "OFFSET {$this->offset}";
        }

        return implode(' ', $sql);
    }

    private function buildWhereClause(): string
    {
        return implode(' AND ', array_map(function($where) {
            [$column, $operator, $value] = $where;
            
            if ($operator === 'IN') {
                $placeholders = rtrim(str_repeat('?,', count($value)), ',');
                return "{$column} IN ({$placeholders})";
            }
            
            if ($operator === 'BETWEEN') {
                return "{$column} BETWEEN ? AND ?";
            }
            
            if ($operator === 'IS') {
                return "{$column} IS {$value}";
            }
            
            return "{$column} {$operator} ?";
        }, $this->wheres));
    }

    private function buildHavingClause(): string
    {
        return implode(' AND ', array_map(function($having) {
            [$column, $operator, $value] = $having;
            return "{$column} {$operator} ?";
        }, $this->havings));
    }

    public function getBindings(): array
    {
        return $this->bindings;
    }
} 