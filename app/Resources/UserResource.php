<?php

namespace App\Resources;

use Core\Api\Resource;

class UserResource extends Resource
{
    public function toArray(): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'created_at' => $this->resource->created_at,
            'profile' => new ProfileResource($this->resource->profile),
            'posts' => PostResource::collection($this->resource->posts)
        ];
    }
} 