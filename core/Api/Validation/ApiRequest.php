<?php

namespace Core\Api\Validation;

abstract class ApiRequest
{
    protected array $data;
    protected array $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    abstract public function rules(): array;

    public function messages(): array
    {
        return [];
    }

    public function validate(): bool
    {
        $validator = app()->validator->make(
            $this->data,
            $this->rules(),
            $this->messages()
        );

        if ($validator->fails()) {
            $this->errors = $validator->errors();
            return false;
        }

        return true;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function validated(): array
    {
        return array_intersect_key($this->data, $this->rules());
    }
} 