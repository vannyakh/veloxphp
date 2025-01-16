<?php

namespace Core\Events;

class Dispatcher
{
    protected array $listeners = [];
    protected array $wildcards = [];

    public function listen($events, $listener): void
    {
        foreach ((array) $events as $event) {
            if (str_contains($event, '*')) {
                $this->wildcards[$event][] = $listener;
            } else {
                $this->listeners[$event][] = $listener;
            }
        }
    }

    public function dispatch($event, array $payload = [])
    {
        $responses = [];

        foreach ($this->getListeners($event) as $listener) {
            $response = $listener($event, $payload);
            if ($response !== null) {
                $responses[] = $response;
            }
        }

        return $responses;
    }

    protected function getListeners(string $eventName): array
    {
        $listeners = $this->listeners[$eventName] ?? [];

        foreach ($this->wildcards as $key => $wildcardListeners) {
            if (str_is($key, $eventName)) {
                $listeners = array_merge($listeners, $wildcardListeners);
            }
        }

        return $listeners;
    }
} 