<?php

namespace Vesper\UnitConversion\Exceptions;

use Exception;

class UnknownUnitException extends Exception
{
    public function __construct(string $unit)
    {
        $message = "Unknown unit: $unit";
        parent::__construct($message);
    }
}