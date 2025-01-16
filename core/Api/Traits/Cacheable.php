<?php

namespace Core\Api\Traits;

trait Cacheable
{
    protected function cacheResponse($data, int $minutes = 60)
    {
        $key = $this->getCacheKey();
        
        return cache()->remember($key, $minutes, function() use ($data) {
            return $this->success($data);
        });
    }

    protected function getCacheKey(): string
    {
        return sprintf(
            '%s.%s.%s.%s',
            request()->path(),
            request()->getMethod(),
            json_encode(request()->all()),
            auth()->id() ?? 'guest'
        );
    }

    protected function clearCache(): void
    {
        cache()->forget($this->getCacheKey());
    }
} 