<?php

namespace Core\Api\QueryParams;

class QueryParameters
{
    protected array $params;
    protected array $allowedIncludes = [];
    protected array $allowedFilters = [];
    protected array $allowedSorts = [];

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function includes(): array
    {
        $includes = explode(',', $this->params['include'] ?? '');
        return array_intersect($includes, $this->allowedIncludes);
    }

    public function filters(): array
    {
        $filters = [];
        foreach ($this->params as $key => $value) {
            if (in_array($key, $this->allowedFilters)) {
                $filters[$key] = $value;
            }
        }
        return $filters;
    }

    public function sort(): array
    {
        $sorts = explode(',', $this->params['sort'] ?? '');
        $validSorts = [];

        foreach ($sorts as $sort) {
            $direction = 'asc';
            if (str_starts_with($sort, '-')) {
                $direction = 'desc';
                $sort = substr($sort, 1);
            }
            if (in_array($sort, $this->allowedSorts)) {
                $validSorts[$sort] = $direction;
            }
        }

        return $validSorts;
    }
} 