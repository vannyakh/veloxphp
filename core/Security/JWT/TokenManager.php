<?php

namespace Core\Security\JWT;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Core\Security\Encryption;

class TokenManager
{
    private string $key;
    private string $algorithm;
    private int $ttl;
    private Encryption $encryption;

    public function __construct(Encryption $encryption)
    {
        $this->key = config('security.jwt.secret');
        $this->algorithm = config('security.jwt.algorithm', 'HS256');
        $this->ttl = config('security.jwt.ttl', 3600);
        $this->encryption = $encryption;
    }

    public function createToken(array $payload): array
    {
        $issuedAt = time();
        $expires = $issuedAt + $this->ttl;

        $tokenPayload = array_merge($payload, [
            'iat' => $issuedAt,
            'exp' => $expires,
            'jti' => $this->generateJTI()
        ]);

        $accessToken = JWT::encode($tokenPayload, $this->key, $this->algorithm);
        $refreshToken = $this->createRefreshToken($tokenPayload['jti']);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'expires_in' => $this->ttl,
            'token_type' => 'Bearer'
        ];
    }

    public function validateToken(string $token): ?array
    {
        try {
            $decoded = (array) JWT::decode($token, new Key($this->key, $this->algorithm));
            return $this->validateClaims($decoded) ? $decoded : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function validateClaims(array $claims): bool
    {
        return isset($claims['exp']) && 
               $claims['exp'] > time() && 
               isset($claims['jti']);
    }

    private function createRefreshToken(string $tokenId): string
    {
        $payload = [
            'jti' => $tokenId,
            'exp' => time() + (86400 * 30) // 30 days
        ];

        return $this->encryption->encrypt(json_encode($payload));
    }

    private function generateJTI(): string
    {
        return bin2hex(random_bytes(16));
    }
} 