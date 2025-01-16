<?php

namespace Database\Seeders;

use Core\Database\Seeder;
use App\Models\Post;
use App\Models\User;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $this->createMany(Post::class, 5, function($faker) use ($user) {
                return [
                    'user_id' => $user->id,
                    'title' => $faker->sentence,
                    'content' => $faker->paragraphs(3, true),
                    'published_at' => $faker->dateTimeBetween('-1 year', 'now'),
                    'status' => $faker->randomElement(['draft', 'published'])
                ];
            });
        }
    }
} 