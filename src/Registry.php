<?php

class Registry {
    private static array $registry = [];

    public static function register(string $name, Type $type, float $scale): void {
        self::$registry[$name] = new UnitPart($name, $type, $scale);
    }

    public static function get(string $key): UnitPart {
        return self::$registry[$key];
    }

    public function registerSiUnit(string $name, Type $type): void
    {
        self::register($name, $type, 1);

        foreach (SiPrefix::cases() as $prefix) {
            $prefixedName = sprintf("%s%s", strtolower($prefix->name), $name);
            self::register($prefixedName, $type, $prefix->value);
        }
    }

    public function init(): void
    {
        $this->registerSiUnit(Type::AREA->value, Type::AREA);
        $this->registerSiUnit(Type::ENERGY->value, Type::ENERGY);
        $this->registerSiUnit(Type::LENGTH->value, Type::LENGTH);
        // The base for mass is kilogram, but kilogram is already prefixed (kilo)
        $this->registerSiUnit('gram', Type::MASS);
        $this->registerSiUnit(Type::TIME->value, Type::TIME);
        $this->registerSiUnit(Type::VOLUME->value, Type::VOLUME);
    }
}