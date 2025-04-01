<?php

namespace Vesper\UnitConversion\Exceptions;

use Exception;

class UnitNameIsAlreadyRegisteredException extends Exception
{
    public function __construct(string $unitName)
    {
        $message = "Name [$unitName] is already registered";
        parent::__construct($message);
    }
}