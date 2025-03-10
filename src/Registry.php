<?php declare(strict_types=1);

namespace Vesper\UnitConversion;

class Registry
{
    protected array $registry = [];

    public function register(string $name, Unit $unit): self
    {
        $this->registry[$name] = $unit;

        return $this;
    }

    public function has(string $name): bool
    {
        return isset($this->registry[$name]);
    }

    public function alias(string $name, array|string $aliases): self
    {
        $base = $this->get($name);

        if ($base === null) {
            throw new \Exception("Cannot alias unknown unit [$name]");
        }

        foreach ((array)$aliases as $alias) {
            if (isset($this->registry[$alias])) {
                throw new \Exception("Name [$alias] is already registered");
            }

            $this->registry[$alias] = $base;
        }

        return $this;
    }

    public function get(string $key): ?Unit
    {
        return $this->registry[$key] ?? null;
    }
}