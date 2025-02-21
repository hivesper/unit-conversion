<?php declare(strict_types=1);

namespace Conversion;

class Converter
{
    public function convert(Unit $from, Unit $to, float $value = 1): float
    {
        if (!$from->canConvertTo($to)) {
            throw new \Exception("Cannot convert from [$from] to [$to]");
        }

        return $value * $from->getRatio() / $to->getRatio();
    }

    /**
     * @return array{0: float, 1: Unit}
     */
    public function multiply(float $multiplicand, Unit $multiplicandUnit, float $multiplier, Unit $multiplierUnit): array
    {
        return [$multiplicand * $multiplier, $multiplicandUnit->multiply($multiplierUnit)];
    }

    /**
     * @return array{0: float, 1: Unit}
     */
    public function divide(float $dividend, Unit $dividendUnit, float $divisor, Unit $divisorUnit): array
    {
        return [$dividend / $divisor, $dividendUnit->divide($divisorUnit)];
    }
}