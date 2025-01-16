<?php

namespace Core\Api\Exceptions;

use Core\Exceptions\Handler;
use Core\Api\Traits\ApiResponse;

class ApiExceptionHandler extends Handler
{
    use ApiResponse;

    protected array $dontReport = [
        ApiException::class,
        ValidationException::class,
    ];

    public function render($request, \Throwable $e)
    {
        if ($this->isApiRequest($request)) {
            return $this->handleApiException($e);
        }

        return parent::render($request, $e);
    }

    protected function handleApiException(\Throwable $e)
    {
        if ($e instanceof ValidationException) {
            return $this->error('Validation failed', 422, $e->errors());
        }

        if ($e instanceof ApiException) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        if ($e instanceof \PDOException) {
            return $this->error('Database error', 500);
        }

        if (config('app.debug')) {
            return $this->error($e->getMessage(), 500, [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace()
            ]);
        }

        return $this->error('Server error', 500);
    }
} 