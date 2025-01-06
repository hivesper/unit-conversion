<?php

namespace Conversion;

class Converter {
    public static function convert(Unit $from, Unit $to, float $value = 1): float
    {
        if (!$from->canConvertTo($to)) {
            throw new \InvalidArgumentException("Cannot convert from [$from] to [$to]");
        }

        return $value;
    }
}