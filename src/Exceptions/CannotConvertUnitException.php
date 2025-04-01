<?php

namespace Vesper\UnitConversion\Exceptions;

use Exception;
use Vesper\UnitConversion\Unit;

class CannotConvertUnitException extends Exception
{
    public function __construct(Unit $fromUnit, Unit $toUnit)
    {
        $message = "Cannot convert from [$fromUnit] to [$toUnit]";
        parent::__construct($message);
    }
}