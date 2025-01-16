<?php

namespace Core\Database;

use Faker\Factory as Faker;

abstract class Seeder
{
    protected $faker;

    public function __construct()
    {
        $this->faker = Faker::create();
    }

    abstract public function run(): void;

    protected function call(string $seeder): void
    {
        $instance = new $seeder();
        $instance->run();
    }

    protected function createMany(string $model, int $count, callable $factory): array
    {
        $items = [];
        for ($i = 0; $i < $count; $i++) {
            $items[] = $model::create($factory($this->faker));
        }
        return $items;
    }
} 