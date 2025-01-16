<?php

namespace Core\Traits;

trait ApiResponse
{
    protected function success($data = [], string $message = '', int $code = 200): array
    {
        return [
            'success' => true,
            'data' => $data,
            'message' => $message,
            'code' => $code
        ];
    }

    protected function error(string $message, int $code = 400, $errors = []): array
    {
        return [
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'code' => $code
        ];
    }

    protected function paginate($items): array
    {
        return [
            'data' => $items->items(),
            'meta' => [
                'current_page' => $items->currentPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
                'last_page' => $items->lastPage()
            ],
            'links' => [
                'first' => $items->url(1),
                'last' => $items->url($items->lastPage()),
                'prev' => $items->previousPageUrl(),
                'next' => $items->nextPageUrl()
            ]
        ];
    }
} 