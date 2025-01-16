<?php

namespace Core;

class Config
{
    private array $config = [];
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function load(): void
    {
        foreach (glob($this->path . '/*.php') as $file) {
            $key = basename($file, '.php');
            $this->config[$key] = require $file;
        }
    }

    public function get(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $value = $this->config;

        foreach ($keys as $segment) {
            if (!isset($value[$segment])) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }

    public function set(string $key, $value): void
    {
        $keys = explode('.', $key);
        $config = &$this->config;

        foreach ($keys as $i => $segment) {
            if ($i === count($keys) - 1) {
                $config[$segment] = $value;
                break;
            }

            if (!isset($config[$segment])) {
                $config[$segment] = [];
            }

            $config = &$config[$segment];
        }
    }
} 