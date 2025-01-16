<?php

namespace Core\Model\Relations;

abstract class Relation
{
    protected $parent;
    protected $related;
    protected string $foreignKey;
    protected string $localKey;

    public function __construct($parent, $related, string $foreignKey, string $localKey)
    {
        $this->parent = $parent;
        $this->related = $related;
        $this->foreignKey = $foreignKey;
        $this->localKey = $localKey;
    }

    abstract public function getResults();
    abstract public function addConstraints();
} 