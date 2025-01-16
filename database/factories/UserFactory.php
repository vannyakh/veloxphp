<?php

namespace Database\Factories;

use Core\Database\Factory;
use App\Models\User;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'role' => 'user',
            'created_at' => $this->faker->dateTimeBetween('-1 year')
        ];
    }

    public function admin(): self
    {
        return $this->state(function($faker) {
            return ['role' => 'admin'];
        });
    }

    public function verified(): self
    {
        return $this->state(function($faker) {
            return ['email_verified_at' => now()];
        });
    }

    public function withProfile(): self
    {
        return $this->afterCreating(function($user) {
            $user->profile()->create([
                'bio' => $this->faker->paragraph,
                'avatar' => $this->faker->imageUrl(),
                'phone' => $this->faker->phoneNumber
            ]);
        });
    }
} 