<?php

namespace Conversion\Facades;

use Illuminate\Support\Facades\Facade;
use Conversion\Converter as ConversionConverter;

class Converter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ConversionConverter::class;
    }
}
