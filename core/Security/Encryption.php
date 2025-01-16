<?php

namespace Core\Security;

class Encryption
{
    private string $key;
    private string $cipher;

    public function __construct()
    {
        $this->key = base64_decode(config('security.encryption.key'));
        $this->cipher = config('security.encryption.cipher', 'AES-256-CBC');
    }

    public function encrypt(string $value): string
    {
        $iv = random_bytes(openssl_cipher_iv_length($this->cipher));
        
        $encrypted = openssl_encrypt(
            $value,
            $this->cipher,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv
        );

        $hmac = hash_hmac('sha256', $encrypted, $this->key, true);
        
        return base64_encode($iv . $hmac . $encrypted);
    }

    public function decrypt(string $payload): ?string
    {
        $decoded = base64_decode($payload);
        
        $ivLength = openssl_cipher_iv_length($this->cipher);
        $hmacLength = 32; // SHA256 length

        $iv = substr($decoded, 0, $ivLength);
        $hmac = substr($decoded, $ivLength, $hmacLength);
        $encrypted = substr($decoded, $ivLength + $hmacLength);

        $calculatedHmac = hash_hmac('sha256', $encrypted, $this->key, true);
        
        if (!hash_equals($hmac, $calculatedHmac)) {
            return null;
        }

        return openssl_decrypt(
            $encrypted,
            $this->cipher,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv
        );
    }
} 