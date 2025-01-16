<?php

namespace Core\Model\Relations;

class HasOne extends Relation
{
    public function getResults()
    {
        return $this->related::query()
            ->where($this->foreignKey, '=', $this->parent->{$this->localKey})
            ->first();
    }

    public function addConstraints()
    {
        $this->related::query()
            ->where($this->foreignKey, '=', $this->parent->{$this->localKey});
    }
} 