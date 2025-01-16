<?php

namespace App\Http\Requests\Api;

use Core\Api\Validation\ApiRequest;

class CreateUserRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'sometimes|string|in:user,admin',
            'profile' => 'sometimes|array',
            'profile.bio' => 'required_with:profile|string',
            'profile.avatar' => 'sometimes|image|max:2048'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please provide a name',
            'email.unique' => 'This email is already registered',
            'password.min' => 'Password must be at least 8 characters'
        ];
    }
} 