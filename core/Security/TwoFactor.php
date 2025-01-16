<?php

namespace Core\Security;

class TwoFactor
{
    private const CODE_LENGTH = 6;
    private const CODE_EXPIRY = 600; // 10 minutes

    public function generateCode(): string
    {
        return str_pad((string) random_int(0, 999999), self::CODE_LENGTH, '0', STR_PAD_LEFT);
    }

    public function storeCode(string $identifier, string $code): void
    {
        $_SESSION['2fa'][$identifier] = [
            'code' => $code,
            'expires' => time() + self::CODE_EXPIRY
        ];
    }

    public function verifyCode(string $identifier, string $code): bool
    {
        if (!isset($_SESSION['2fa'][$identifier])) {
            return false;
        }

        $stored = $_SESSION['2fa'][$identifier];
        
        if (time() > $stored['expires']) {
            unset($_SESSION['2fa'][$identifier]);
            return false;
        }

        $valid = hash_equals($stored['code'], $code);
        unset($_SESSION['2fa'][$identifier]);
        
        return $valid;
    }
} 