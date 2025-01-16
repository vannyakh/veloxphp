<?php

namespace Core\Model\Relations;

class HasMany extends Relation
{
    public function getResults()
    {
        return $this->related::query()
            ->where($this->foreignKey, '=', $this->parent->{$this->localKey})
            ->get();
    }

    public function addConstraints()
    {
        $this->related::query()
            ->where($this->foreignKey, '=', $this->parent->{$this->localKey});
    }
} 