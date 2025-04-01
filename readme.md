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

$unit = $parser->parse('m/s'); // Return class Unit
```

### Regular Conversion

```php
use Vesper\UnitConversion\Converter;

$converter = new Converter();
$cm = $parser->parse('cm');
$m = $parser->parse('m');

$result = $converter->convert($cm, $m, 100); // Converts 100 cm to meters
```

### Compound Unit Conversion

```php
$ms = $parser->parse('m/s');
$kmh = $parser->parse('km/h');

$result = $converter->convert($ms, $kmh, 10); // Converts 10 m/s to km/h
```

### Creating and Adding Custom Units

```php
use Vesper\UnitConversion\RegistryBuilder;
use Vesper\UnitConversion\Dimension;

RegistryBuilder::registerSiUnit(
    $registry,
    'mile',
    symbols: ['mi'],
    ratio: 1609.34, // 1 mile = 1609.34 meters
    dimension: Dimension::LENGTH
);
```

### Using in Laravel

To use this package in Laravel, bind the `Converter` and `Parser` classes in a service provider:

```php
use Illuminate\Support\ServiceProvider;
use Vesper\UnitConversion\Converter;
use Vesper\UnitConversion\Parser;
use Vesper\UnitConversion\Registry;

class UnitConversionServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Registry::class, function () {
            return new Registry();
        });
        
        $this->app->singleton(Parser::class, function ($app) {
            return new Parser($app->make(Registry::class));
        });
        
        $this->app->singleton(Converter::class, function () {
            return new Converter();
        });
    }
}
```

Then, you can use dependency injection in your controllers or services:

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

        return $this->converter->convert($from, $to, $value); // Converts 5 kg to grams
    }
}
```

## License

This package is open-source and available under the MIT License. See the [LICENSE](./LICENSE) file for more details.


