<?php

namespace Database\Seeders;

use Core\Database\Seeder;
use App\Models\User;
use Database\Factories\UserFactory;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin users
        (new UserFactory())
            ->count(2)
            ->admin()
            ->verified()
            ->withProfile()
            ->create();

        // Create regular users
        (new UserFactory())
            ->count(10)
            ->verified()
            ->withProfile()
            ->create();

        // Create unverified users
        (new UserFactory())
            ->count(5)
            ->create();
    }
} 