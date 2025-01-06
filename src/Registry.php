<?php

namespace Conversion;

class Registry {
    private static array $registry = [];

    public static function register(string $name, Type $type, float $ratio): void {
        self::$registry[$name] = new UnitPart($name, $type, $ratio);
    }

    public static function get(string $key): ?UnitPart {
        return self::$registry[$key] ?? null;
    }

    public function registerSiUnit(string $name, Type $type): void
    {
        self::register($name, $type, 1);

        foreach (SiPrefix::cases() as $prefix) {
            $prefixedName = sprintf("%s%s", strtolower($prefix->name), $name);
            self::register($prefixedName, $type, 10 ** $prefix->value);
        }
    }

    public function init(): void
    {
        $this->registerSiUnit(Type::AREA->value, Type::AREA);
        $this->registerSiUnit(Type::ENERGY->value, Type::ENERGY);
        $this->registerSiUnit(Type::LENGTH->value, Type::LENGTH);
        $this->registerSiUnit('gram', Type::MASS);
        // Add other time units (hours etc) and relation to base
        $this->registerSiUnit(Type::TIME->value, Type::TIME);

        // Liters
        $this->registerSiUnit(Type::VOLUME->value, Type::VOLUME);
    }
}