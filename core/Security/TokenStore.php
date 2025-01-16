<?php

namespace Core\Security;

use Core\Cache\Cache;

class TokenStore
{
    private Cache $cache;
    private int $defaultExpiry = 3600;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    public function createToken(string $purpose, array $data = [], int $expiry = null): string
    {
        $token = $this->generateToken();
        $expiry = $expiry ?? $this->defaultExpiry;

        $this->cache->put(
            $this->getKey($token, $purpose),
            array_merge($data, ['created_at' => time()]),
            $expiry
        );

        return $token;
    }

    public function validateToken(string $token, string $purpose, bool $oneTime = true): ?array
    {
        $key = $this->getKey($token, $purpose);
        $data = $this->cache->get($key);

        if ($oneTime) {
            $this->cache->forget($key);
        }

        return $data;
    }

    private function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    private function getKey(string $token, string $purpose): string
    {
        return "token:{$purpose}:{$token}";
    }
} 