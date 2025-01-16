<?php

namespace App\Webhooks;

interface WebhookHandlerInterface
{
    /**
     * Handle the webhook event
     *
     * @param array $payload The webhook payload
     * @param array $headers The webhook headers
     * @return mixed
     */
    public function handle(array $payload, array $headers);
    
    /**
     * Verify the webhook signature
     *
     * @param string $signature
     * @param string $payload
     * @return bool
     */
    public function verifySignature(string $signature, string $payload): bool;
} 