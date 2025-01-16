<?php

use Core\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Call your seeders here
        $this->call(UserSeeder::class);
        $this->call(PostSeeder::class);
    }
} 