<?php

namespace Core\Api;

use Core\Cache\Cache;

class RateLimiter
{
    private Cache $cache;
    private int $maxAttempts;
    private int $decayMinutes;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
        $this->maxAttempts = config('api.rate_limit.max_attempts', 60);
        $this->decayMinutes = config('api.rate_limit.decay_minutes', 1);
    }

    public function attempt(string $key): bool
    {
        $attempts = $this->attempts($key);

        if ($attempts >= $this->maxAttempts) {
            return false;
        }

        $this->hit($key);
        return true;
    }

    public function attempts(string $key): int
    {
        return (int) $this->cache->get($this->key($key), 0);
    }

    public function hit(string $key): void
    {
        $key = $this->key($key);
        $attempts = $this->attempts($key) + 1;
        
        $this->cache->put($key, $attempts, $this->decayMinutes * 60);
    }

    public function resetAttempts(string $key): void
    {
        $this->cache->forget($this->key($key));
    }

    public function remaining(string $key): int
    {
        return $this->maxAttempts - $this->attempts($key);
    }

    private function key(string $key): string
    {
        return 'rate_limit:' . sha1($key);
    }
} 