<?php

namespace Vesper\UnitConversion\Exceptions;

use Exception;

class InvalidUnitException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}