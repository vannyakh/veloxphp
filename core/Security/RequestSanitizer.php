<?php

namespace Core\Security;

class RequestSanitizer
{
    private array $defaultFilters = [
        'email' => FILTER_SANITIZE_EMAIL,
        'url' => FILTER_SANITIZE_URL,
        'int' => FILTER_SANITIZE_NUMBER_INT,
        'float' => FILTER_SANITIZE_NUMBER_FLOAT,
        'string' => FILTER_SANITIZE_STRING,
        'encoded' => FILTER_SANITIZE_ENCODED
    ];

    public function sanitize(array $data, array $rules = []): array
    {
        $sanitized = [];

        foreach ($data as $key => $value) {
            if (isset($rules[$key])) {
                $sanitized[$key] = $this->applySanitization($value, $rules[$key]);
            } else {
                $sanitized[$key] = $this->defaultSanitize($value);
            }
        }

        return $sanitized;
    }

    private function applySanitization($value, string $rule)
    {
        if (is_array($value)) {
            return array_map(fn($item) => $this->applySanitization($item, $rule), $value);
        }

        $filters = explode('|', $rule);
        
        foreach ($filters as $filter) {
            $value = match ($filter) {
                'trim' => trim($value),
                'lowercase' => strtolower($value),
                'uppercase' => strtoupper($value),
                'escape' => htmlspecialchars($value, ENT_QUOTES, 'UTF-8'),
                'alpha' => preg_replace('/[^a-zA-Z]/', '', $value),
                'alphanumeric' => preg_replace('/[^a-zA-Z0-9]/', '', $value),
                'numeric' => preg_replace('/[^0-9.]/', '', $value),
                'email' => filter_var($value, FILTER_SANITIZE_EMAIL),
                'url' => filter_var($value, FILTER_SANITIZE_URL),
                default => $value
            };
        }

        return $value;
    }

    private function defaultSanitize($value)
    {
        if (is_array($value)) {
            return array_map([$this, 'defaultSanitize'], $value);
        }

        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
} 