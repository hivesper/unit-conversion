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

    public static function registerSiUnit(Registry $registry, string $name, array $symbols, float $ratio, Dimension $dimension, int $power): void
    {
        $registry->register($name, [new UnitPart($ratio, $dimension, $power)]);

        if ($symbols) {
            $registry->alias($name, $symbols);
        }

        foreach (static::$siPrefixes as $prefix) {
            $prefixedName = "{$prefix['name']}$name";
            $registry->register($prefixedName, [new UnitPart(
                $ratio * 10 ** ($prefix['factor']),
                $dimension,
                $power
            )]);

            $aliases = array_map(fn($symbol) => "{$prefix['short_name']}$symbol", $symbols);
            $registry->alias($prefixedName, $aliases);
        }
    }

    protected static function initArea(Registry $registry): void
    {
        // todo: hectare, etc
    }

    protected static function initEnergy(Registry $registry): void
    {
        $registry->register('joule', [
            new UnitPart(1, Dimension::MASS, 1),
            new UnitPart(1, Dimension::LENGTH, 2),
            new UnitPart(1, Dimension::TIME, -2)
        ]);
    }

    protected static function initLength(Registry $registry): void
    {
        static::registerSiUnit($registry, 'meter', ['m'], 1, Dimension::LENGTH, 1);
    }

    protected static function initMass(Registry $registry): void
    {
        static::registerSiUnit($registry, 'gram', ['g'], 0.001, Dimension::MASS, 1);
    }

    protected static function initTime(Registry $registry): void
    {
        $registry->register('second', [new UnitPart(1, Dimension::TIME, 1)]);
        $registry->register('minute', [new UnitPart(60, Dimension::TIME, 1)]);
        $registry->register('hour', [new UnitPart(3600, Dimension::TIME, 1)]);
        $registry->register('day', [new UnitPart(86400, Dimension::TIME, 1)]);
    }

    protected static function initVolume(Registry $registry): void
    {
        $registry->register('liter', [
            new UnitPart(0.001, Dimension::LENGTH, 3),
        ]);
    }
}