<?php

namespace Conversion;

class Converter
{
    public function __construct(protected Registry $registry)
    {

    }

    public function convert(Unit $from, Unit $to, float $value = 1): float
    {
        if (!$from->canConvertTo($to)) {
            throw new \Exception("Cannot convert from [$from] to [$to]");
        }

        if ($from->isCompound()) {
            return $this->convertCompoundUnit($from, $to, $value);
        }

        return $this->convertPart($from->getPart(0), $to->getPart(0), $value);
    }

    protected function convertPart(UnitPart $from, UnitPart $to, float $value = 1): float
    {
        return $value * $from->getRatio() / $to->getRatio();
    }

    protected function convertCompoundUnit(Unit $from, Unit $to, float $value = 1): float
    {
        $ratio = $this->convertPart($from->getPart(0), $to->getPart(0));

        for ($i = 1; $i < count($from->getParts()); $i++) {
            $ratio /= $this->convertPart($from->getPart($i), $to->getPart($i));
        }

        return $value / $ratio;
    }
}