<?php

namespace App\Support;

use ReflectionClass;
use ReflectionParameter;

class Container
{
    protected array $bindings = [];
    protected array $instances = [];

    public function bind(string $abstract, string|\Closure $concrete, bool $singleton = false): void
    {
        $this->bindings[$abstract] = compact('concrete', 'singleton');
    }

    public function singleton(string $abstract, string|\Closure $concrete): void
    {
        $this->bind($abstract, $concrete, true);
    }

    public function make(string $abstract)
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        if (!isset($this->bindings[$abstract])) {
            return $this->build($abstract);
        }

        $concrete = $this->bindings[$abstract]['concrete'];

        if ($concrete instanceof \Closure) {
            $object = $concrete($this);
        } else {
            $object = $this->build($concrete);
        }

        if ($this->bindings[$abstract]['singleton']) {
            $this->instances[$abstract] = $object;
        }

        return $object;
    }

    protected function build(string $class)
    {
        $reflector = new ReflectionClass($class);

        if (!$reflector->isInstantiable()) {
            throw new \Exception("Class {$class} is not instantiable");
        }

        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return new $class;
        }

        $dependencies = array_map(
            fn(ReflectionParameter $param) => $this->make($param->getType()->getName()),
            $constructor->getParameters()
        );

        return $reflector->newInstanceArgs($dependencies);
    }
}
