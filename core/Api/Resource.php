<?php

namespace Core\Api;

abstract class Resource
{
    protected $resource;
    protected array $includes = [];
    protected array $availableIncludes = [];

    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    abstract public function toArray(): array;

    public function with(array $includes): self
    {
        $this->includes = array_intersect($includes, $this->availableIncludes);
        return $this;
    }

    public static function collection($collection): array
    {
        return array_map(function ($item) {
            return (new static($item))->toArray();
        }, $collection);
    }

    protected function when($condition, $value, $default = null)
    {
        return $condition ? $value : $default;
    }

    protected function mergeWhen($condition, array $data): array
    {
        return $condition ? $data : [];
    }

    protected function include($relation): ?array
    {
        if (!in_array($relation, $this->includes)) {
            return null;
        }

        $method = 'include' . ucfirst($relation);
        return method_exists($this, $method) ? $this->$method() : null;
    }
} 