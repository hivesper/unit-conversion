# Unit Conversion Library for PHP

## Introduction

This package provides a powerful and flexible way to handle unit conversion in PHP. It supports parsing units from strings, performing conversions, and working with both simple and compound units.

## Why Use This Library?

- **Simple string-based unit parsing**
- **Supports SI prefixes and derived units**
- **Handles compound unit conversions** (e.g., speed, density, force)
- **Extendable** - Easily add new units and dimensions
- **Integration with Laravel**

## Installation

Install via Composer:

```sh
  composer require vesper/unit-conversion
```

## Usage

### Parsing a Unit

```php
use Vesper\UnitConversion\Parser;
use Vesper\UnitConversion\Registry;

$registry = new Registry();
$parser = new Parser($registry);

$unit = $parser->parse('m/s'); // Returns an instance of Unit
```

### Regular Conversion

```php
use Vesper\UnitConversion\Converter;

$converter = new Converter();
$cm = $parser->parse('cm');
$m = $parser->parse('m');

$result = $converter->convert($cm, $m, 100); // Converts 100cm to 1m
```

### Compound Unit Conversion

```php
$ms = $parser->parse('m/s');
$kmh = $parser->parse('km/h');

$result = $converter->convert($ms, $kmh, 10); // Converts 10m/s to 36km/h
```

### Creating and Adding Custom Units

The `RegistryBuilder` class provides a set of predefined units and allows for easy extension.

#### Using `RegistryBuilder::build()`

The `build()` method initializes a `Registry` instance with common units like length, mass, time, temperature, force, etc.

```php
use Vesper\UnitConversion\Registry;
use Vesper\UnitConversion\RegistryBuilder;

$registry = new Registry();
RegistryBuilder::build($registry);
```

This method ensures that a wide range of units are available for use immediately.

#### Registering a Custom Unit

The `register()` method is used to add new units to the registry, and `alias()` allows you to specify alternative names.

```php
use Vesper\UnitConversion\Registry;
use Vesper\UnitConversion\Unit;
use Vesper\UnitConversion\Dimension;
use Vesper\UnitConversion\UnitPart;

$registry = new Registry();

$registry->register(
    'furlong',
    new Unit(new UnitPart(1, Dimension::LENGTH, 1)
);
$registry->alias('furlong', ['fur']);
```

#### Using `registerSiUnit()`

The `registerSiUnit()` method allows you to define SI units along with their prefixed versions (e.g., kilogram, milligram, etc.).

```php
use Vesper\UnitConversion\RegistryBuilder;
use Vesper\UnitConversion\Unit;
use Vesper\UnitConversion\UnitPart;
use Vesper\UnitConversion\Dimension;

RegistryBuilder::registerSiUnit(
    $registry,
    'gram', // Base unit
    ['g'], // Aliases
    new Unit(new UnitPart(1, Dimension::MASS, 1))
);
```

This automatically registers `gram` and generates prefixed versions like `kilogram (kg)`, `milligram (mg)`, etc.

### Using in Laravel

This package provides a service provider, `ConversionServiceProvider`, which is auto-discovered by Laravel. This means you don’t need to manually register it.

```php
public function register(): void
{
    $this->app->singleton(Converter::class, function () {
        return new Converter();
    });

    $this->app->singleton(Parser::class, function (Application $app) {
        $registry = $app->make(Registry::class);

        return new Parser($registry);
    });

    $this->app->singleton(Registry::class, function () {
        $registry = new Registry();

        RegistryBuilder::build($registry);

        return $registry;
    });
}
```
Additionally, a facade `Converter` is provided for easy access.

#### Example Usage

```php
use Vesper\UnitConversion\Converter;
use Vesper\UnitConversion\Parser;

class UnitController
{
    public function __construct(
        protected Converter $converter,
        protected Parser $parser
    ) {}

    public function convert()
    {
        $from = $this->parser->parse('kg');
        $to = $this->parser->parse('g');
        $value = 5;

        return $this->converter->convert($from, $to, $value); // Converts 5kg to 5000g
    }
}
```

## License

This package is open-source and available under the MIT License. See the [LICENSE](./LICENSE) file for more details.

