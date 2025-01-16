<?php

namespace Tests\Feature\Api;

use Core\Testing\TestCase;
use App\Models\User;

class UserControllerTest extends TestCase
{
    public function test_can_list_users()
    {
        // Arrange
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password'
        ]);

        // Act
        $response = $this->get('/api/users', [
            'Accept' => 'application/json'
        ]);

        // Assert
        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'created_at'
                    ]
                ],
                'meta' => [
                    'current_page',
                    'per_page',
                    'total'
                ]
            ]);
    }

    public function test_can_create_user()
    {
        $response = $this->post('/api/users', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password123'
        ], [
            'Accept' => 'application/json'
        ]);

        $response->assertCreated()
            ->assertJson([
                'success' => true,
                'message' => 'User created successfully'
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at'
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com'
        ]);
    }

    public function test_validates_required_fields()
    {
        $response = $this->post('/api/users', [], [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed'
            ])
            ->assertJsonStructure([
                'errors' => [
                    'name',
                    'email',
                    'password'
                ]
            ]);
    }
} 