<?php

namespace Core\Security;

class PasswordHasher
{
    private int $algo;
    private array $options;

    public function __construct()
    {
        $this->algo = PASSWORD_ARGON2ID;
        $this->options = [
            'memory_cost' => 65536,
            'time_cost' => 4,
            'threads' => 3
        ];
    }

    public function hash(string $password): string
    {
        return password_hash($password, $this->algo, $this->options);
    }

    public function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public function needsRehash(string $hash): bool
    {
        return password_needs_rehash($hash, $this->algo, $this->options);
    }
} 