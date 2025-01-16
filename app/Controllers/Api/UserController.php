<?php

namespace App\Controllers\Api;

use Core\Controller;
use App\Models\User;
use App\Transformers\UserTransformer;
use App\Http\Requests\Api\CreateUserRequest;
use Core\Api\QueryParams\QueryParameters;
use Core\Api\Traits\{ApiResponse, Cacheable};

class UserController extends Controller
{
    use ApiResponse, Cacheable;

    protected UserTransformer $transformer;
    protected QueryParameters $queryParams;

    public function __construct()
    {
        $this->transformer = new UserTransformer();
        $this->queryParams = new QueryParameters($this->request->all());
        $this->queryParams->allowedIncludes(['profile', 'posts', 'roles']);
        $this->queryParams->allowedFilters(['role', 'status', 'created_at']);
        $this->queryParams->allowedSorts(['name', 'email', 'created_at']);
    }

    public function index()
    {
        $users = User::query()
            ->with($this->queryParams->includes())
            ->filter($this->queryParams->filters())
            ->sort($this->queryParams->sort())
            ->paginate();

        return $this->cacheResponse(
            $this->transformer->with($this->queryParams->includes())
                ->collection($users->items()),
            $users->meta()
        );
    }

    public function store(CreateUserRequest $request)
    {
        if (!$request->validate()) {
            return $this->error('Validation failed', 422, $request->errors());
        }

        $user = User::create($request->validated());
        
        if ($request->has('profile')) {
            $user->profile()->create($request->input('profile'));
        }

        $this->clearCache();

        return $this->success(
            $this->transformer->transform($user),
            'User created successfully',
            201
        );
    }
} 