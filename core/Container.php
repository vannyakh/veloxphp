<?php

namespace Core;

use Psr\Container\ContainerInterface;
use Core\Exceptions\ContainerException;

class Container implements ContainerInterface
{
    private array $bindings = [];
    private array $instances = [];

    public function bind(string $abstract, $concrete = null, bool $shared = false): void
    {
        if (is_null($concrete)) {
            $concrete = $abstract;
        }

        $this->bindings[$abstract] = [
            'concrete' => $concrete,
            'shared' => $shared,
        ];
    }

    public function singleton(string $abstract, $concrete = null): void
    {
        $this->bind($abstract, $concrete, true);
    }

    public function get(string $id)
    {
        if ($this->has($id)) {
            return $this->resolve($id);
        }

        throw new ContainerException("No binding found for {$id}");
    }

    public function has(string $id): bool
    {
        return isset($this->bindings[$id]) || isset($this->instances[$id]);
    }

    private function resolve(string $abstract)
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $binding = $this->bindings[$abstract];
        $concrete = $binding['concrete'];

        if ($concrete instanceof \Closure) {
            $object = $concrete($this);
        } else {
            $object = $this->build($concrete);
        }

        if ($binding['shared']) {
            $this->instances[$abstract] = $object;
        }

        return $object;
    }

    private function build(string $concrete)
    {
        $reflector = new \ReflectionClass($concrete);
        
        if (!$reflector->isInstantiable()) {
            throw new ContainerException("Class {$concrete} is not instantiable");
        }

        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            return new $concrete;
        }

        $dependencies = array_map(function(\ReflectionParameter $param) {
            $type = $param->getType();
            if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                return $this->get($type->getName());
            }
            
            if ($param->isDefaultValueAvailable()) {
                return $param->getDefaultValue();
            }

            throw new ContainerException("Cannot resolve parameter {$param->getName()}");
        }, $constructor->getParameters());

        return $reflector->newInstanceArgs($dependencies);
    }
} 