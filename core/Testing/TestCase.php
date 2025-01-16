<?php

namespace Core\Testing;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected $app;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app = require __DIR__ . '/../../bootstrap/app.php';
        $this->app->boot();
    }

    protected function makeRequest(string $method, string $uri, array $data = [], array $headers = []): TestResponse
    {
        $_SERVER['REQUEST_METHOD'] = $method;
        $_SERVER['REQUEST_URI'] = $uri;
        $_POST = $data;
        $_GET = [];

        foreach ($headers as $key => $value) {
            $_SERVER['HTTP_' . strtoupper(str_replace('-', '_', $key))] = $value;
        }

        $response = $this->app->handle();
        return new TestResponse($response);
    }

    protected function get(string $uri, array $headers = []): TestResponse
    {
        return $this->makeRequest('GET', $uri, [], $headers);
    }

    protected function post(string $uri, array $data = [], array $headers = []): TestResponse
    {
        return $this->makeRequest('POST', $uri, $data, $headers);
    }

    protected function put(string $uri, array $data = [], array $headers = []): TestResponse
    {
        return $this->makeRequest('PUT', $uri, $data, $headers);
    }

    protected function delete(string $uri, array $headers = []): TestResponse
    {
        return $this->makeRequest('DELETE', $uri, [], $headers);
    }
} 