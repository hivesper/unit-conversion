<?php

namespace Vesper\UnitConversion\Exceptions;

use Exception;

class CannotAliasUnknownUnitException extends Exception
{
    public function __construct(string $unitName)
    {
        $message = "Cannot alias unknown unit [$unitName]";
        parent::__construct($message);
    }
}