<?php

namespace Core\Security;

class CSRF
{
    private const TOKEN_LENGTH = 32;

    public function generateToken(): string
    {
        $token = bin2hex(random_bytes(self::TOKEN_LENGTH));
        $_SESSION['csrf_token'] = $token;
        return $token;
    }

    public function validateToken(?string $token): bool
    {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }

        $valid = hash_equals($_SESSION['csrf_token'], $token ?? '');
        unset($_SESSION['csrf_token']); // One-time use
        return $valid;
    }

    public function getTokenField(): string
    {
        $token = $this->generateToken();
        return sprintf(
            '<input type="hidden" name="_token" value="%s">',
            htmlspecialchars($token, ENT_QUOTES, 'UTF-8')
        );
    }
} 