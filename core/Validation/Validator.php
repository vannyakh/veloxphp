<?php

namespace Core\Validation;

class Validator
{
    protected array $data;
    protected array $rules;
    protected array $messages = [];
    protected array $attributes = [];

    public function make(array $data, array $rules): self
    {
        $this->data = $data;
        $this->rules = $rules;

        foreach ($this->rules as $attribute => $rules) {
            foreach ((array) $rules as $rule) {
                $this->validateAttribute($attribute, $rule);
            }
        }

        return $this;
    }

    protected function validateAttribute(string $attribute, string $rule): void
    {
        [$rule, $parameters] = $this->parseRule($rule);
        $value = $this->getValue($attribute);

        if (!$this->{'validate' . ucfirst($rule)}($value, $parameters)) {
            $this->addError($attribute, $rule, $parameters);
        }
    }

    public function fails(): bool
    {
        return !empty($this->messages);
    }

    public function errors(): array
    {
        return $this->messages;
    }

    protected function validateRequired($value): bool
    {
        return $value !== null && $value !== '';
    }

    protected function validateEmail($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    protected function validateMin($value, array $parameters): bool
    {
        return strlen($value) >= $parameters[0];
    }

    protected function validateMax($value, array $parameters): bool
    {
        return strlen($value) <= $parameters[0];
    }
} 