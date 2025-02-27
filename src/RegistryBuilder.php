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
        static::initTemperature($registry);

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
                $ratio * 10 ** $prefix['factor'],
                $dimension,
                $power
            )]);

            $aliases = array_map(fn($symbol) => "{$prefix['short_name']}$symbol", $symbols);
            $registry->alias($prefixedName, $aliases);
        }
    }

    protected static function initArea(Registry $registry): void
    {
        $registry->register('square_meter', [new UnitPart(1, Dimension::AREA, 2)]);
        $registry->alias('square_meter', ['m2']);

        $registry->register('square_inch', [new UnitPart(0.00064516, Dimension::AREA, 2)]);
        $registry->alias('square_inch', ['sqin']);

        $registry->register('square_foot', [new UnitPart(0.09290304, Dimension::AREA, 2)]);
        $registry->alias('square_foot', ['sqft']);

        $registry->register('square_yard', [new UnitPart(0.83612736, Dimension::AREA, 2)]);
        $registry->alias('square_yard', ['sqyd']);

        $registry->register('square_mile', [new UnitPart(2589988.110336, Dimension::AREA, 2)]);
        $registry->alias('square_mile', ['sqmi']);

        $registry->register('square_rod', [new UnitPart(25.29295, Dimension::AREA, 2)]);
        $registry->alias('square_rod', ['sqrd']);

        $registry->register('square_chain', [new UnitPart(404.6873, Dimension::AREA, 2)]);
        $registry->alias('square_chain', ['sqch']);

        $registry->register('square_mil', [new UnitPart(6.4516e-10, Dimension::AREA, 2)]);
        $registry->alias('square_mil', ['sqmil']);

        $registry->register('acre', [new UnitPart(4046.86, Dimension::AREA, 2)]);

        $registry->register('hectare', [new UnitPart(10000, Dimension::AREA, 2)]);
    }

    protected static function initEnergy(Registry $registry): void
    {
        $registry->register('joule', [
            new UnitPart(1, Dimension::MASS, 1),
            new UnitPart(1, Dimension::LENGTH, 2),
            new UnitPart(1, Dimension::TIME, -2)
        ]);

        $registry->register('watt', [
            new UnitPart(1, Dimension::MASS, 1),
            new UnitPart(1, Dimension::LENGTH, 2),
            new UnitPart(1, Dimension::TIME, -3)
        ]);
    }

    protected static function initLength(Registry $registry): void
    {
        static::registerSiUnit($registry, 'meter', ['m'], 1, Dimension::LENGTH, 1);

        $registry->register('inch', [new UnitPart(0.0254, Dimension::LENGTH, 1)]);
        $registry->alias('inch', ['in']);

        $registry->register('foot', [new UnitPart(0.3048, Dimension::LENGTH, 1)]);
        $registry->alias('foot', ['fe']);

        $registry->register('yard', [new UnitPart(0.9144, Dimension::LENGTH, 1)]);
        $registry->alias('yard', ['yd']);

        $registry->register('mile', [new UnitPart(1609.344, Dimension::LENGTH, 1)]);
        $registry->alias('mile', ['mi']);

        $registry->register('link', [new UnitPart(0.201168, Dimension::LENGTH, 1)]);
        $registry->alias('link', ['li']);

        $registry->register('rod', [new UnitPart(5.0292, Dimension::LENGTH, 1)]);
        $registry->alias('rod', ['rd']);

        $registry->register('chain', [new UnitPart(20.1168, Dimension::LENGTH, 1)]);
        $registry->alias('chain', ['cn']);

        $registry->register('angstrom', [new UnitPart(1e-10, Dimension::LENGTH, 1)]);

        $registry->register('mil', [new UnitPart(0.0000254, Dimension::LENGTH, 1)]);
    }

    protected static function initMass(Registry $registry): void
    {
        static::registerSiUnit($registry, 'gram', ['g'], 0.001, Dimension::MASS, 1);

        $registry->register('ton', [new UnitPart(907.18474, Dimension::MASS, 1)]);

        $registry->register('tonne', [new UnitPart(1000, Dimension::MASS, 1)]);
        $registry->alias('tonne', ['t']);

        $registry->register('grain', [new UnitPart(64.79891e-6, Dimension::MASS, 1)]);
        $registry->alias('grain', ['gr']);

        $registry->register('dram', [new UnitPart(1.7718451953125e-3, Dimension::MASS, 1)]);
        $registry->alias('dram', ['dr']);

        $registry->register('ounce', [new UnitPart(28.349523125e-3, Dimension::MASS, 1)]);
        $registry->alias('ounce', ['oz']);

        $registry->register('poundmass', [new UnitPart(453.59237e-3, Dimension::MASS, 1)]);
        $registry->alias('poundmass', ['lbm']);

        $registry->register('hundredweight', [new UnitPart(45.359237, Dimension::MASS, 1)]);
        $registry->alias('hundredweight', ['cwt']);

        $registry->register('stick', [new UnitPart(115e-3, Dimension::MASS, 1)]);

        $registry->register('stone', [new UnitPart(6.35029318, Dimension::MASS, 1)]);
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
        $registry->register('liter', [new UnitPart(0.1, Dimension::LENGTH, 3)]);
        $registry->alias('liter', ['l']);

        $registry->register('m3', [new UnitPart(1, Dimension::LENGTH, 3)]);
    }

    protected static function initTemperature(Registry $registry): void
    {
        $registry->register('kelvin', [new UnitPart(1, Dimension::TEMPERATURE, 1)]);
        $registry->register('celsius', [new UnitPart(1, Dimension::TEMPERATURE, 1, 273.15)]);
        $registry->register('fahrenheit', [new UnitPart(5 / 9, Dimension::TEMPERATURE, 1, 459.67)]);
    }
}