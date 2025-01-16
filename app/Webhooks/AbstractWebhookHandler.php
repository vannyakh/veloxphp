<?php

namespace App\Webhooks;

use Core\Http\Request;
use Core\Support\Facades\Log;

abstract class AbstractWebhookHandler implements WebhookHandlerInterface
{
    protected string $secret;
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->secret = $config['secret'] ?? '';
    }

    /**
     * Process the webhook
     */
    public function process(Request $request)
    {
        try {
            $payload = $request->getBody();
            $signature = $request->header('X-Webhook-Signature');

            if (!$this->verifySignature($signature, $payload)) {
                Log::warning('Invalid webhook signature', [
                    'signature' => $signature,
                    'payload' => $payload
                ]);
                return response()->json(['error' => 'Invalid signature'], 401);
            }

            $result = $this->handle(
                json_decode($payload, true),
                $request->headers()
            );

            Log::info('Webhook processed successfully', [
                'handler' => static::class,
                'result' => $result
            ]);

            return response()->json(['status' => 'success', 'data' => $result]);
        } catch (\Exception $e) {
            Log::error('Webhook processing failed', [
                'handler' => static::class,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Verify HMAC signature
     */
    public function verifySignature(string $signature, string $payload): bool
    {
        if (empty($this->secret)) {
            return true;
        }

        $computedSignature = hash_hmac('sha256', $payload, $this->secret);
        return hash_equals($computedSignature, $signature);
    }
} 