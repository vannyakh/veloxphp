<?php

namespace Core\Security;

class HeaderManager
{
    private array $config;
    private array $customHeaders = [];

    public function __construct()
    {
        $this->config = config('security.headers');
    }

    public function addCustomHeader(string $name, string $value): self
    {
        $this->customHeaders[$name] = $value;
        return $this;
    }

    public function apply(): void
    {
        $this->applySecurityHeaders();
        $this->applyCSPHeaders();
        $this->applyCustomHeaders();
    }

    private function applySecurityHeaders(): void
    {
        $headers = [
            'X-Frame-Options' => 'SAMEORIGIN',
            'X-XSS-Protection' => '1; mode=block',
            'X-Content-Type-Options' => 'nosniff',
            'X-Permitted-Cross-Domain-Policies' => 'none',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Clear-Site-Data' => '"cache","cookies","storage"',
            'Cross-Origin-Embedder-Policy' => 'require-corp',
            'Cross-Origin-Opener-Policy' => 'same-origin',
            'Cross-Origin-Resource-Policy' => 'same-origin',
            'Permissions-Policy' => $this->getPermissionsPolicy()
        ];

        foreach ($headers as $name => $value) {
            header("$name: $value");
        }
    }

    private function applyCSPHeaders(): void
    {
        if (!($this->config['csp']['enabled'] ?? false)) {
            return;
        }

        $policy = $this->buildCSPPolicy();
        $headerName = $this->config['csp']['report_only'] ? 
            'Content-Security-Policy-Report-Only' : 
            'Content-Security-Policy';

        header("$headerName: $policy");
    }

    private function buildCSPPolicy(): string
    {
        $directives = $this->config['csp']['directives'] ?? [];
        $policy = [];

        foreach ($directives as $directive => $sources) {
            $policy[] = $directive . ' ' . implode(' ', $sources);
        }

        if ($reportUri = $this->config['csp']['report_uri']) {
            $policy[] = "report-uri $reportUri";
        }

        return implode('; ', $policy);
    }

    private function getPermissionsPolicy(): string
    {
        return implode(', ', [
            'accelerometer=()',
            'camera=()',
            'geolocation=()',
            'gyroscope=()',
            'magnetometer=()',
            'microphone=()',
            'payment=()',
            'usb=()'
        ]);
    }

    private function applyCustomHeaders(): void
    {
        foreach ($this->customHeaders as $name => $value) {
            header("$name: $value");
        }
    }
} 