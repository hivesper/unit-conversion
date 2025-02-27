<?php

namespace Conversion\Facades;

use Illuminate\Support\Facades\Facade;

class RegistryFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'registry';
    }
}
