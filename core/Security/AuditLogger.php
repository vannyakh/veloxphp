<?php

namespace Core\Security;

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\JsonFormatter;

class AuditLogger
{
    private Logger $logger;
    private array $context;

    public function __construct()
    {
        $this->logger = new Logger('security');
        $this->setupLogger();
        $this->context = [
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'timestamp' => time()
        ];
    }

    public function logLogin(string $userId, bool $success, string $reason = null): void
    {
        $this->log('login_attempt', [
            'user_id' => $userId,
            'success' => $success,
            'reason' => $reason
        ]);
    }

    public function logPasswordChange(string $userId): void
    {
        $this->log('password_change', [
            'user_id' => $userId
        ]);
    }

    public function logAccessDenied(string $resource, string $reason): void
    {
        $this->log('access_denied', [
            'resource' => $resource,
            'reason' => $reason
        ]);
    }

    public function logSuspiciousActivity(string $type, array $details): void
    {
        $this->log('suspicious_activity', [
            'type' => $type,
            'details' => $details
        ]);
    }

    private function log(string $event, array $data): void
    {
        $this->logger->info($event, array_merge(
            $this->context,
            $data,
            ['event' => $event]
        ));
    }

    private function setupLogger(): void
    {
        $handler = new RotatingFileHandler(
            storage_path('logs/security.log'),
            30,
            Logger::INFO
        );
        $handler->setFormatter(new JsonFormatter());
        $this->logger->pushHandler($handler);
    }
} 