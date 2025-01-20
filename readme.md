
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
$converter = new Converter($registry);

// Simple units
$converter->convert(
    $parser->parse('cm'),
    $parser->parse('m'),
    100
);

// Compound units
$converter->convert(
    $parser->parse('m/s'),
    $parser->parse('km/h'),
);

// Units with ratios
$converter
    ->withRatios([
        // density of gold
        'kilogram/meter^3' => 19320
    ])
    ->convert(
        $parser->parse('g'),
        $parser->parse('cm^3'),
    )
```