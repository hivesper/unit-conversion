<?php

namespace Conversion\Facades;

use Illuminate\Support\Facades\Facade;

class ConverterFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'converter';
    }
}
