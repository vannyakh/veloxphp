<?php

namespace Core\Api\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtAuth
{
    protected string $key;
    protected string $algorithm;
    protected int $ttl;

    public function __construct()
    {
        $this->key = config('jwt.secret');
        $this->algorithm = config('jwt.algorithm', 'HS256');
        $this->ttl = config('jwt.ttl', 60 * 24); // 24 hours
    }

    public function createToken(array $payload): string
    {
        $payload['iat'] = time();
        $payload['exp'] = time() + ($this->ttl * 60);

        return JWT::encode($payload, $this->key, $this->algorithm);
    }

    public function validateToken(string $token): ?array
    {
        try {
            return (array) JWT::decode($token, new Key($this->key, $this->algorithm));
        } catch (\Exception $e) {
            return null;
        }
    }

    public function refreshToken(string $token): ?string
    {
        $payload = $this->validateToken($token);
        if ($payload) {
            unset($payload['iat'], $payload['exp']);
            return $this->createToken($payload);
        }
        return null;
    }
} 