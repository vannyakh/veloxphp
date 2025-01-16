<?php

namespace Core\Security;

use Core\Cache\Cache;

class RateLimiter
{
    private Cache $cache;
    private array $limits = [];

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
        $this->limits = [
            'api' => ['attempts' => 60, 'decay' => 60], // 60 requests per minute
            'login' => ['attempts' => 5, 'decay' => 300], // 5 attempts per 5 minutes
            'register' => ['attempts' => 3, 'decay' => 3600], // 3 attempts per hour
        ];
    }

    public function attempt(string $key, string $type = 'api'): bool
    {
        $identifier = $this->getIdentifier($key);
        $limit = $this->limits[$type] ?? $this->limits['api'];

        $attempts = (int) $this->cache->get($identifier, 0);
        
        if ($attempts >= $limit['attempts']) {
            return false;
        }

        $this->cache->put($identifier, $attempts + 1, $limit['decay']);
        return true;
    }

    public function remaining(string $key, string $type = 'api'): int
    {
        $identifier = $this->getIdentifier($key);
        $limit = $this->limits[$type] ?? $this->limits['api'];
        $attempts = (int) $this->cache->get($identifier, 0);
        
        return max(0, $limit['attempts'] - $attempts);
    }

    private function getIdentifier(string $key): string
    {
        return 'rate_limit:' . sha1($key . $_SERVER['REMOTE_ADDR']);
    }
} 