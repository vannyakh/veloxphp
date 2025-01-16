<?php

namespace Core\Security;

class PasswordPolicy
{
    private array $config;

    public function __construct()
    {
        $this->config = config('security.password', [
            'min_length' => 8,
            'require_uppercase' => true,
            'require_numeric' => true,
            'require_special' => true,
            'max_age' => 90, // days
            'history' => 3 // remember last 3 passwords
        ]);
    }

    public function validate(string $password): array
    {
        $errors = [];

        if (strlen($password) < $this->config['min_length']) {
            $errors[] = "Password must be at least {$this->config['min_length']} characters";
        }

        if ($this->config['require_uppercase'] && !preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter";
        }

        if ($this->config['require_numeric'] && !preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one number";
        }

        if ($this->config['require_special'] && !preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = "Password must contain at least one special character";
        }

        return $errors;
    }

    public function needsChange(string $lastChanged): bool
    {
        if (!$this->config['max_age']) {
            return false;
        }

        $lastChangedTime = strtotime($lastChanged);
        $maxAge = $this->config['max_age'] * 86400; // convert days to seconds

        return (time() - $lastChangedTime) > $maxAge;
    }

    public function checkHistory(string $password, array $history): bool
    {
        foreach ($history as $oldHash) {
            if (password_verify($password, $oldHash)) {
                return false;
            }
        }
        return true;
    }
} 