<?php

namespace Core\Testing;

class TestResponse
{
    protected $response;
    protected $decoded;

    public function __construct($response)
    {
        $this->response = $response;
        $this->decoded = json_decode($response->getContent(), true);
    }

    public function assertOk(): self
    {
        PHPUnit\Framework\Assert::assertEquals(200, $this->response->getStatusCode());
        return $this;
    }

    public function assertCreated(): self
    {
        PHPUnit\Framework\Assert::assertEquals(201, $this->response->getStatusCode());
        return $this;
    }

    public function assertNotFound(): self
    {
        PHPUnit\Framework\Assert::assertEquals(404, $this->response->getStatusCode());
        return $this;
    }

    public function assertJson(array $data): self
    {
        PHPUnit\Framework\Assert::assertArraySubset(
            $data,
            $this->decoded,
            true
        );
        return $this;
    }

    public function assertJsonStructure(array $structure): self
    {
        $this->assertArrayHasKeys($structure, $this->decoded);
        return $this;
    }

    protected function assertArrayHasKeys(array $keys, array $array): void
    {
        foreach ($keys as $key => $value) {
            if (is_array($value) && is_array($array[$key])) {
                $this->assertArrayHasKeys($value, $array[$key]);
            } else {
                PHPUnit\Framework\Assert::assertArrayHasKey($key, $array);
            }
        }
    }
} 