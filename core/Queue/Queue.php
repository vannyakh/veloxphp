<?php

namespace Core\Queue;

abstract class Queue
{
    protected $connection;
    protected $default = 'default';

    public function push(Job $job, string $queue = null): string
    {
        return $this->pushRaw($this->createPayload($job), $queue ?? $this->default);
    }

    public function later(\DateTime $delay, Job $job, string $queue = null): string
    {
        return $this->pushRaw(
            $this->createPayload($job),
            $queue ?? $this->default,
            ['delay' => $delay]
        );
    }

    abstract protected function pushRaw(string $payload, string $queue, array $options = []): string;
    abstract public function pop(string $queue = null): ?Job;

    protected function createPayload(Job $job): string
    {
        return json_encode([
            'job' => get_class($job),
            'data' => $job->getData(),
            'attempts' => 0,
            'created_at' => time()
        ]);
    }
} 