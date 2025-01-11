<?php declare(strict_types=1);

namespace Conversion;

class RegistryBuilder
{
    public static array $siPrefixes = [
        ['name' => 'quetta', 'short_name' => 'Q', 'factor' => 30],
        ['name' => 'ronna', 'short_name' => 'R', 'factor' => 27],
        ['name' => 'yotta', 'short_name' => 'Y', 'factor' => 24],
        ['name' => 'zetta', 'short_name' => 'Z', 'factor' => 21],
        ['name' => 'exa', 'short_name' => 'E', 'factor' => 18],
        ['name' => 'peta', 'short_name' => 'P', 'factor' => 15],
        ['name' => 'tera', 'short_name' => 'T', 'factor' => 12],
        ['name' => 'giga', 'short_name' => 'G', 'factor' => 9],
        ['name' => 'mega', 'short_name' => 'M', 'factor' => 6],
        ['name' => 'kilo', 'short_name' => 'k', 'factor' => 3],
        ['name' => 'hecto', 'short_name' => 'h', 'factor' => 2],
        ['name' => 'deca', 'short_name' => 'da', 'factor' => 1],
        ['name' => 'deci', 'short_name' => 'd', 'factor' => -1],
        ['name' => 'centi', 'short_name' => 'c', 'factor' => -2],
        ['name' => 'milli', 'short_name' => 'm', 'factor' => -3],
        ['name' => 'micro', 'short_name' => 'μ', 'factor' => -6],
        ['name' => 'nano', 'short_name' => 'n', 'factor' => -9],
        ['name' => 'pico', 'short_name' => 'p', 'factor' => -12],
        ['name' => 'femto', 'short_name' => 'f', 'factor' => -15],
        ['name' => 'atto', 'short_name' => 'a', 'factor' => -18],
        ['name' => 'zepto', 'short_name' => 'z', 'factor' => -21],
        ['name' => 'yocto', 'short_name' => 'y', 'factor' => -24],
        ['name' => 'ronto', 'short_name' => 'r', 'factor' => -27],
        ['name' => 'quecto', 'short_name' => 'q', 'factor' => -30],
    ];

    public static function build(Registry $registry): Registry
    {
        static::initArea($registry);
        static::initEnergy($registry);
        static::initLength($registry);
        static::initMass($registry);
        static::initTime($registry);
        static::initVolume($registry);

        return $registry;
    }

    public static function registerSiUnit(Registry $registry, Type $type, string $name, array $symbols = [], float $ratio = 1, float $power = 1): void
    {
        $registry->register($name, $type, $ratio);

        if ($symbols) {
            $registry->alias($name, $symbols);
        }

        foreach (static::$siPrefixes as $prefix) {
            $prefixedName = "{$prefix['name']}$name";
            $registry->register($prefixedName, $type, $ratio * 10 ** ($prefix['factor'] * $power));

            $aliases = array_map(fn($symbol) => "{$prefix['short_name']}$symbol", $symbols);
            $registry->alias($prefixedName, $aliases);
        }
    }

    protected static function initArea(Registry $registry): void
    {
        static::registerSiUnit($registry, Type::AREA, Type::AREA->value, ['m^2', 'm2'], ratio: 2, power: 2);
    }

    protected static function initEnergy(Registry $registry): void
    {
        static::registerSiUnit($registry, Type::ENERGY, Type::ENERGY->value, ['j', 'J']);
    }

    protected static function initLength(Registry $registry): void
    {
        static::registerSiUnit($registry, Type::LENGTH, Type::LENGTH->value, ['m']);
    }

    protected static function initMass(Registry $registry): void
    {
        static::registerSiUnit($registry, Type::MASS, 'gram', ['g'], ratio: 0.001 );
    }

    protected static function initTime(Registry $registry): void
    {
        static::registerSiUnit($registry, Type::TIME, Type::TIME->value, ['s']);

        $registry->register('minute', Type::TIME, ratio: 60);
        $registry->register('hour', Type::TIME, ratio: 3600);
        $registry->register('day', Type::TIME, ratio: 86400);
        $registry->register('week', Type::TIME, ratio: 604800);
        $registry->register('year', Type::TIME, ratio: 31536000);
    }

    protected static function initVolume(Registry $registry): void
    {
        static::registerSiUnit($registry, Type::VOLUME, Type::VOLUME->value, ['m^3', 'm3'], power: 3);

        $registry->alias('decimeter^3', ['l', 'L', 'liter']);
    }
}