<?php

namespace App\Controllers\Api;

use Core\Controller;
use App\Models\User;
use App\Resources\UserResource;
use App\Http\Requests\User\CreateUserRequest;
use Core\Api\Traits\{ApiResponse, Cacheable};

class UserController extends Controller
{
    use ApiResponse, Cacheable;

    public function index()
    {
        $users = User::query()
            ->when($this->request->has('search'), function($query) {
                $search = $this->request->get('search');
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->when($this->request->has('role'), function($query) {
                $query->where('role', $this->request->get('role'));
            })
            ->paginate();

        return $this->cacheResponse(
            UserResource::collection($users),
            'Users retrieved successfully'
        );
    }

    public function store(CreateUserRequest $request)
    {
        if (!$request->validate()) {
            return $this->error('Validation failed', 422, $request->errors());
        }

        $user = User::create($request->validated());
        
        $this->clearCache();

        return $this->success(
            new UserResource($user),
            'User created successfully',
            201
        );
    }
} 