<?php declare(strict_types=1);

namespace Vesper\UnitConversion;

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

    public static function registerSiUnit(Registry $registry, string $name, array $symbols, Unit $unit): void
    {
        $registry->register($name, $unit);

        if ($symbols) {
            $registry->alias($name, $symbols);
        }

        foreach (static::$siPrefixes as $prefix) {
            $prefixedName = "{$prefix['name']}$name";
            $registry->register($prefixedName, new Unit(
                new FactorUnitPart(10 ** $prefix['factor']),
                ...$unit->getParts()
            ));

            $aliases = array_map(fn($symbol) => "{$prefix['short_name']}$symbol", $symbols);
            $registry->alias($prefixedName, $aliases);
        }
    }

    protected static function initArea(Registry $registry): void
    {
        static::registerSiUnit(
            $registry,
            'meter^2',
            ['m2'],
            new Unit(new UnitPart(1, Dimension::LENGTH, 2))
        );

        $registry->register('inch^2', new Unit(new UnitPart(sqrt(0.00064516), Dimension::LENGTH, 2)));
        $registry->alias('inch^2', ['in2']);

        $registry->register('foot^2', new Unit(new UnitPart(sqrt(0.09290304), Dimension::LENGTH, 2)));
        $registry->alias('foot^2', ['ft2']);

        $registry->register('yard^2', new Unit(new UnitPart(sqrt(0.83612736), Dimension::LENGTH, 2)));
        $registry->alias('yard^2', ['yd2']);

        $registry->register('mile^2', new Unit(new UnitPart(sqrt(2589988.110336), Dimension::LENGTH, 2)));
        $registry->alias('mile^2', ['mi2']);

        $registry->register('rod^2', new Unit(new UnitPart(sqrt(25.29295), Dimension::LENGTH, 2)));
        $registry->alias('rod^2', ['rd2']);

        $registry->register('chain^2', new Unit(new UnitPart(sqrt(404.6873), Dimension::LENGTH, 2)));
        $registry->alias('chain^2', ['ch2']);

        $registry->register('mil^2', new Unit(new UnitPart(sqrt(6.4516e-10), Dimension::LENGTH, 2)));
        $registry->alias('mil^2', ['mil2']);

        $registry->register('acre', new Unit(new UnitPart(sqrt(63.63), Dimension::LENGTH, 2)));

        $registry->register('hectare', new Unit(new UnitPart(100, Dimension::LENGTH, 2)));
    }

    protected static function initEnergy(Registry $registry): void
    {
        static::registerSiUnit(
            $registry,
            'joule',
            ['J'],
            new Unit(
                new UnitPart(1, Dimension::MASS, 1),
                new UnitPart(1, Dimension::LENGTH, 2),
                new UnitPart(1, Dimension::TIME, -2)
            )
        );

        static::registerSiUnit(
            $registry,
            'calorie',
            ['cal'],
            new Unit(
                new FactorUnitPart(4.1868),
                new UnitPart(1, Dimension::MASS, 1),
                new UnitPart(1, Dimension::LENGTH, 2),
                new UnitPart(1, Dimension::TIME, -2)
            )
        );
    }

    protected static function initLength(Registry $registry): void
    {
        static::registerSiUnit(
            $registry,
            'meter',
            ['m'],
            new Unit(new UnitPart(1, Dimension::LENGTH, 1))
        );

        $registry->register('inch', new Unit(new UnitPart(0.0254, Dimension::LENGTH, 1)));
        $registry->alias('inch', ['in']);

        $registry->register('foot', new Unit(new UnitPart(0.3048, Dimension::LENGTH, 1)));
        $registry->alias('foot', ['fe']);

        $registry->register('yard', new Unit(new UnitPart(0.9144, Dimension::LENGTH, 1)));
        $registry->alias('yard', ['yd']);

        $registry->register('mile', new Unit(new UnitPart(1609.344, Dimension::LENGTH, 1)));
        $registry->alias('mile', ['mi']);

        $registry->register('link', new Unit(new UnitPart(0.201168, Dimension::LENGTH, 1)));
        $registry->alias('link', ['li']);

        $registry->register('rod', new Unit(new UnitPart(5.0292, Dimension::LENGTH, 1)));
        $registry->alias('rod', ['rd']);

        $registry->register('chain', new Unit(new UnitPart(20.1168, Dimension::LENGTH, 1)));
        $registry->alias('chain', ['cn']);

        $registry->register('angstrom', new Unit(new UnitPart(1e-10, Dimension::LENGTH, 1)));

        $registry->register('mil', new Unit(new UnitPart(0.0000254, Dimension::LENGTH, 1)));
    }

    protected static function initMass(Registry $registry): void
    {
        static::registerSiUnit(
            $registry,
            'gram',
            ['g'],
            new Unit(new UnitPart(0.001, Dimension::MASS, 1))
        );

        $registry->register('ton', new Unit(new UnitPart(907.18474, Dimension::MASS, 1)));

        $registry->register('tonne', new Unit(new UnitPart(1000, Dimension::MASS, 1)));
        $registry->alias('tonne', ['t']);

        $registry->register('grain', new Unit(new UnitPart(64.79891e-6, Dimension::MASS, 1)));
        $registry->alias('grain', ['gr']);

        $registry->register('dram', new Unit(new UnitPart(1.7718451953125e-3, Dimension::MASS, 1)));
        $registry->alias('dram', ['dr']);

        $registry->register('ounce', new Unit(new UnitPart(28.349523125e-3, Dimension::MASS, 1)));
        $registry->alias('ounce', ['oz']);

        $registry->register('pound', new Unit(new UnitPart(0.45359237, Dimension::MASS, 1)));
        $registry->alias('pound', ['lb', 'lbs', 'lbm', 'poundmass']);

        $registry->register('hundredweight', new Unit(new UnitPart(45.359237, Dimension::MASS, 1)));
        $registry->alias('hundredweight', ['cwt']);

        $registry->register('stick', new Unit(new UnitPart(115e-3, Dimension::MASS, 1)));

        $registry->register('stone', new Unit(new UnitPart(6.35029318, Dimension::MASS, 1)));
    }

    protected static function initTime(Registry $registry): void
    {
        $registry->register('second', new Unit(new UnitPart(1, Dimension::TIME, 1)));
        $registry->register('minute', new Unit(new UnitPart(60, Dimension::TIME, 1)));
        $registry->register('hour', new Unit(new UnitPart(3600, Dimension::TIME, 1)));
        $registry->register('day', new Unit(new UnitPart(86400, Dimension::TIME, 1)));
    }

    protected static function initVolume(Registry $registry): void
    {
        static::registerSiUnit(
            $registry,
            'meter^3',
            ['m3'],
            new Unit(new UnitPart(1, Dimension::LENGTH, 3))
        );

        $registry->register('liter', new Unit(new UnitPart(0.1, Dimension::LENGTH, 3)));
        $registry->alias('liter', ['l', 'litre']);
    }

    protected static function initTemperature(Registry $registry): void
    {
        $registry->register('kelvin', new Unit(new UnitPart(1, Dimension::TEMPERATURE, 1)));
        $registry->register('celsius', new Unit(new UnitPart(1, Dimension::TEMPERATURE, 1, 273.15)));
        $registry->register('fahrenheit', new Unit(new UnitPart(5 / 9, Dimension::TEMPERATURE, 1, 459.67)));
    }
}