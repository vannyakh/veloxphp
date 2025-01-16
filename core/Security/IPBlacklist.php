<?php

namespace Core\Security;

use Core\Cache\Cache;

class IPBlacklist
{
    private Cache $cache;
    private array $config;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
        $this->config = config('security.ip_blacklist', [
            'max_failures' => 5,
            'block_duration' => 3600,
            'whitelist' => ['127.0.0.1']
        ]);
    }

    public function isBlocked(string $ip): bool
    {
        if (in_array($ip, $this->config['whitelist'])) {
            return false;
        }

        $key = "ip_blacklist:{$ip}";
        return (bool) $this->cache->get($key);
    }

    public function recordFailure(string $ip): void
    {
        $key = "ip_failures:{$ip}";
        $failures = (int) $this->cache->get($key, 0) + 1;
        
        $this->cache->put($key, $failures, $this->config['block_duration']);

        if ($failures >= $this->config['max_failures']) {
            $this->blockIP($ip);
        }
    }

    public function blockIP(string $ip, int $duration = null): void
    {
        $duration = $duration ?? $this->config['block_duration'];
        $this->cache->put("ip_blacklist:{$ip}", true, $duration);
    }

    public function unblockIP(string $ip): void
    {
        $this->cache->forget("ip_blacklist:{$ip}");
        $this->cache->forget("ip_failures:{$ip}");
    }
} 