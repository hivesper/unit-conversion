<?php

namespace Conversion;

class Converter {
    public function __construct(protected Registry $registry)
    {

    }

    public function convert(Unit $from, Unit $to, float $value = 1): float
    {
        if (!$from->canConvertTo($to)) {
            throw new \InvalidArgumentException("Cannot convert from [$from] to [$to]");
        }

        foreach ($from->getParts() as $index => $part) {
            $value = $this->convertPart($part, $to->getParts()[$index], $value);
        }

        return $value;
    }

    protected function convertPart(UnitPart $from, UnitPart $to, float $value = 1): float
    {
        return $value * $from->getRatio() / $to->getRatio();
    }
}