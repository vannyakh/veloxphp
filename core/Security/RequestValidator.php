<?php

namespace Core\Security;

class RequestValidator
{
    private SQLSanitizer $sqlSanitizer;
    private XSS $xss;

    public function __construct(SQLSanitizer $sqlSanitizer, XSS $xss)
    {
        $this->sqlSanitizer = $sqlSanitizer;
        $this->xss = $xss;
    }

    public function validate(array $data, array $rules): array
    {
        $errors = [];
        $sanitizedData = [];

        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            $ruleSet = explode('|', $rule);

            foreach ($ruleSet as $singleRule) {
                if ($error = $this->validateRule($field, $value, $singleRule)) {
                    $errors[$field][] = $error;
                }
            }

            if (!isset($errors[$field])) {
                $sanitizedData[$field] = $this->sanitize($value, $ruleSet);
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'data' => $sanitizedData
        ];
    }

    private function validateRule(string $field, $value, string $rule): ?string
    {
        if (strpos($rule, ':') !== false) {
            [$rule, $parameter] = explode(':', $rule);
        }

        switch ($rule) {
            case 'required':
                return empty($value) ? "The {$field} field is required" : null;
            
            case 'email':
                return !filter_var($value, FILTER_VALIDATE_EMAIL) ? 
                    "The {$field} must be a valid email" : null;
            
            case 'url':
                return !filter_var($value, FILTER_VALIDATE_URL) ? 
                    "The {$field} must be a valid URL" : null;
            
            case 'no_script':
                return preg_match('/<script/i', $value) ? 
                    "The {$field} cannot contain script tags" : null;
            
            case 'alpha_numeric':
                return !ctype_alnum($value) ? 
                    "The {$field} must only contain letters and numbers" : null;
        }

        return null;
    }

    private function sanitize($value, array $rules): mixed
    {
        if (in_array('sql_safe', $rules)) {
            $value = $this->sqlSanitizer->sanitize($value);
        }

        if (in_array('xss_clean', $rules)) {
            $value = $this->xss->clean($value);
        }

        return $value;
    }
} 