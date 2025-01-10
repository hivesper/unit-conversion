```php
<?php

use Conversion\Converter;
use Conversion\Parser;
use Conversion\Registry;

$registry = new Registry();
// Setup basic units
$registry->init();

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