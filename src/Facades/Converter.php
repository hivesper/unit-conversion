<?php

namespace Conversion\Facades;

use Illuminate\Support\Facades\Facade;
use Vesper\UnitConversion\Converter as VesperConverter;

class Converter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return VesperConverter::class;
    }
}
