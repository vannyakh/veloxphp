<?php

namespace Core\Security;

class SQLSanitizer
{
    public function sanitize($value)
    {
        if (is_array($value)) {
            return array_map([$this, 'sanitize'], $value);
        }

        if (is_string($value)) {
            return $this->sanitizeString($value);
        }

        return $value;
    }

    private function sanitizeString(string $value): string
    {
        // Remove common SQL injection patterns
        $patterns = [
            '/\b(UNION|SELECT|INSERT|UPDATE|DELETE|DROP|TRUNCATE)\b/i',
            '/[\'";\\\]/',
            '/\/\*.*\*\//',  // Remove SQL comments
            '/--.*$/'        // Remove single line comments
        ];

        $value = preg_replace($patterns, '', $value);
        return addslashes($value);
    }
} 