<?php

namespace Core\Api\Filters;

use Core\Database\QueryBuilder;

abstract class Filter
{
    protected array $filters = [];
    protected QueryBuilder $query;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function apply(QueryBuilder $query): QueryBuilder
    {
        $this->query = $query;

        foreach ($this->filters as $name => $value) {
            $method = 'filter' . ucfirst($name);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }

        return $this->query;
    }

    protected function filterSearch(string $keyword): void
    {
        $this->query->where(function($query) use ($keyword) {
            foreach ($this->getSearchableFields() as $field) {
                $query->orWhere($field, 'LIKE', "%{$keyword}%");
            }
        });
    }

    protected function filterSort(string $field): void
    {
        if (in_array($field, $this->getSortableFields())) {
            $direction = $this->filters['direction'] ?? 'asc';
            $this->query->orderBy($field, $direction);
        }
    }

    abstract protected function getSearchableFields(): array;
    abstract protected function getSortableFields(): array;
} 