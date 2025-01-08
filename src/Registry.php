<?php

namespace Conversion;

class Registry {
    protected array $registry = [];

    protected array $siPrefixes;

    public function __construct()
    {
        $this->siPrefixes = require __DIR__ . '/si-prefixes.php';
    }

    public function register(string $name, Type $type, float $ratio): self{
        $this->registry[$name] = new UnitPart($name, $type, $ratio);

        return $this;
    }

    public function alias(string $name, array $aliases): self
    {
        $base = $this->get($name);

        if ($base === null) {
            throw new \Exception("Cannot alias unknown unit [$name]");
        }

        foreach ($aliases as $alias) {
            if (isset($this->registry[$alias])) {
                throw new \Exception("Adding [$alias] for [$name] would overwrite [{$this->registry[$alias]}]");
            }

            $this->registry[$alias] = $base;
        }

        return $this;
    }

    public function get(string $key): ?UnitPart {
        return $this->registry[$key] ?? null;
    }

    public function registerSiUnit(string $name, ?array $symbols, Type $type, float $ratio): self
    {
        $this->register($name, $type, $ratio);

        foreach ($this->siPrefixes as $prefix) {
            $prefixedName = sprintf("%s%s", $prefix['name'], $name);

            $this->register($prefixedName, $type, $ratio * 10 ** $prefix['value']);

            if ($symbols) {
                $this->alias($prefixedName, array_map(fn($symbol) => $prefix['short_name'] . $symbol, $symbols));
            }
        }

        return $this;
    }

    public function init(): void
    {
        $this->initArea();
        $this->initEnergy();
        $this->initLength();
        $this->initMass();
        $this->initTime();
        $this->initVolume();
    }

    protected function initArea(): void
    {
        $this->registerSiUnit(Type::AREA->value, ['m^2', 'm2'], Type::AREA, 1);
    }

    protected function initEnergy(): void
    {
        $this->registerSiUnit(Type::ENERGY->value, ['j', 'J'], Type::ENERGY, 1);
    }

    protected function initLength(): void
    {
        $this->registerSiUnit(Type::LENGTH->value, ['m'], Type::LENGTH, 1);
    }

    protected function initMass(): void
    {
        $this->registerSiUnit('gram', ['g'], Type::MASS, 0.001);
    }

    protected function initTime(): void
    {
        $this->registerSiUnit(Type::TIME->value, ['s'], Type::TIME, 1);

        $this->register('minute', Type::TIME, 60);
        $this->register('hour', Type::TIME, 3600);
        $this->register('day', Type::TIME, 86400);
        $this->register('week', Type::TIME, 604800);
        $this->register('year', Type::TIME, 31536000);
    }

    protected function initVolume(): void
    {
        $this
            ->registerSiUnit(Type::VOLUME->value,  ['m^3', 'm3'], Type::VOLUME, 1)
            ->alias('decimeter^3', ['l', 'L']);
    }
}