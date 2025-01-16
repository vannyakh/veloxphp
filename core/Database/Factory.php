<?php

namespace Core\Database;

use Faker\Factory as Faker;

abstract class Factory
{
    protected $faker;
    protected $count = 1;
    protected $states = [];
    protected $afterCreating = [];
    protected $afterMaking = [];

    public function __construct()
    {
        $this->faker = Faker::create();
    }

    abstract public function definition(): array;

    public function count(int $count): self
    {
        $this->count = $count;
        return $this;
    }

    public function state(callable $state): self
    {
        $this->states[] = $state;
        return $this;
    }

    public function afterCreating(callable $callback): self
    {
        $this->afterCreating[] = $callback;
        return $this;
    }

    public function afterMaking(callable $callback): self
    {
        $this->afterMaking[] = $callback;
        return $this;
    }

    public function make(array $attributes = []): array
    {
        $items = [];
        for ($i = 0; $i < $this->count; $i++) {
            $item = array_merge($this->definition(), $attributes);
            
            foreach ($this->states as $state) {
                $item = array_merge($item, $state($this->faker));
            }

            $items[] = $item;
            
            foreach ($this->afterMaking as $callback) {
                $callback($item);
            }
        }
        return $items;
    }

    public function create(array $attributes = []): array
    {
        $items = [];
        $model = $this->getModel();

        foreach ($this->make($attributes) as $item) {
            $instance = $model::create($item);
            $items[] = $instance;

            foreach ($this->afterCreating as $callback) {
                $callback($instance);
            }
        }

        return $items;
    }

    protected function getModel(): string
    {
        $class = get_class($this);
        return str_replace('Factory', '', $class);
    }
} 