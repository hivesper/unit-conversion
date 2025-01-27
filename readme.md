
# Units in php
## to do:
- [ ] Add more units
- [ ] Add conversion ratios

```php
<?php

use Conversion\Converter;
use Conversion\Dimension;
use Conversion\Parser;
use Conversion\Registry;
use Conversion\RegistryBuilder;

$registry = new Registry();

// Setup basic units
RegistryBuilder::build($registry);

// Register a si unit including all the si prefixes.
// When specifying symbols, it will also add prefixed symbols.
RegistryBuilder::registerSiUnit(
    $registry,
    'meter^2', // Full name of the unit
    symbols: ['m^2'], // Symbols for the unit
    ratio: 1 // The relation to the base unit
    dimension: Dimension::LENGTH
    power: 2
);

$parser = new Parser($registry);
$converter = new Converter();

// Simple units
$cm = $parser->parse('cm');
$m = $parser->parse('m');

$converter->convert($cm, $m, 100);

// Compound units
$ms = $parser->parse('m/s');
$kmh = $parser->parse('km/h'),

$converter->convert($ms, $kmh);

// Units with ratios
$kg = $parser->parse('kg');
$m3 = $parser->parse('m^3');

$converter
    ->withRatio($m3, $kg, fn ($value) => $value / 19320)
    ->convert(
        $parser->parse('g'),
        $parser->parse('cm^3'),
    )
```