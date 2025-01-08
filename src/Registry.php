<?php

namespace Conversion;

class Registry {
    protected array $registry = [];

    public function register(string $name, Type $type, float $ratio): void {
        $this->registry[$name] = new UnitPart($name, $type, $ratio);
    }

    public function get(string $key): ?UnitPart {
        return $this->registry[$key] ?? null;
    }

    public function registerSiUnit(string $name, Type $type): void
    {
        $this->register($name, $type, 1);

        foreach (SiPrefix::cases() as $prefix) {
            $prefixedName = sprintf("%s%s", strtolower($prefix->name), $name);
            $this->register($prefixedName, $type, 10 ** $prefix->value);
        }
    }

    public function init(): void
    {
        $this->registerSiUnit(Type::AREA->value, Type::AREA);
        $this->registerSiUnit(Type::ENERGY->value, Type::ENERGY);
        $this->registerSiUnit(Type::LENGTH->value, Type::LENGTH);
        $this->registerSiUnit('gram', Type::MASS);

        $this->initTime();

        // Liters
        $this->registerSiUnit(Type::VOLUME->value, Type::VOLUME);
    }

    protected function initTime(): void
    {
        $this->registerSiUnit(Type::TIME->value, Type::TIME);

        $this->register('minute', Type::TIME, 60);
        $this->register('hour', Type::TIME, 3600);
        $this->register('day', Type::TIME, 86400);
        $this->register('week', Type::TIME, 604800);
        $this->register('year', Type::TIME, 31536000);
    }
}