<?php declare(strict_types=1);

namespace Conversion;

class Registry {
    protected array $registry = [];

    /**
     * @param string $name
     * @param UnitPart[] $parts
     * @return Registry
     */
    public function register(string $name, array $parts): self {
        $this->registry[$name] = $parts;

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
                throw new \Exception("Name [$alias] is already registered");
            }

            $this->registry[$alias] = $base;
        }

        return $this;
    }

    /**
     * @return UnitPart[]|null
     */
    public function get(string $key): ?array
    {
        return $this->registry[$key] ?? null;
    }
}