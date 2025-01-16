<?php

namespace Core\Api\Transformer;

abstract class Transformer
{
    protected array $includes = [];
    protected array $availableIncludes = [];

    public function transform($item): array
    {
        return array_merge(
            $this->toArray($item),
            $this->includeRelations($item)
        );
    }

    abstract protected function toArray($item): array;

    public function collection($items): array
    {
        return array_map(fn($item) => $this->transform($item), $items);
    }

    public function with(array $includes): self
    {
        $this->includes = array_intersect($includes, $this->availableIncludes);
        return $this;
    }

    protected function includeRelations($item): array
    {
        $includes = [];
        foreach ($this->includes as $include) {
            $method = 'include' . ucfirst($include);
            if (method_exists($this, $method)) {
                $includes[$include] = $this->$method($item);
            }
        }
        return $includes;
    }
} 