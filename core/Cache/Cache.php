<?php

namespace Core\Cache;

class Cache
{
    private $store;
    private $prefix;

    public function __construct(CacheStore $store, string $prefix = '')
    {
        $this->store = $store;
        $this->prefix = $prefix;
    }

    public function get(string $key, $default = null)
    {
        $value = $this->store->get($this->prefix . $key);
        return $value !== null ? $this->unserialize($value) : $default;
    }

    public function put(string $key, $value, int $ttl = 3600): bool
    {
        return $this->store->put(
            $this->prefix . $key,
            $this->serialize($value),
            $ttl
        );
    }

    public function remember(string $key, int $ttl, \Closure $callback)
    {
        $value = $this->get($key);

        if ($value !== null) {
            return $value;
        }

        $value = $callback();
        $this->put($key, $value, $ttl);
        return $value;
    }

    public function forget(string $key): bool
    {
        return $this->store->forget($this->prefix . $key);
    }

    public function flush(): bool
    {
        return $this->store->flush();
    }

    private function serialize($value): string
    {
        return serialize($value);
    }

    private function unserialize(string $value)
    {
        return unserialize($value);
    }
} 