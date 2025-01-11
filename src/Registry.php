<?php

namespace Conversion;

class Registry {
    protected array $registry = [];

    public function register(string $name, Type $type, float $ratio): self{
        $this->registry[$name] = new UnitPart($name, $type, $ratio);

        return $this;
    }

    public function alias(string $name, array|string $aliases): self
    {
        $base = $this->get($name);

        if ($base === null) {
            throw new \Exception("Cannot alias unknown unit [$name]");
        }

        foreach ((array)$aliases as $alias) {
            if (isset($this->registry[$alias])) {
                throw new \Exception("Adding [$alias] for [$name] would overwrite [{$this->get($alias)}]");
            }

            $this->registry[$alias] = $base;
        }

        return $this;
    }

    public function get(string $key): ?UnitPart {
        return $this->registry[$key] ?? null;
    }
}