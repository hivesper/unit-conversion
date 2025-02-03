<?php declare(strict_types=1);

namespace Conversion;

class Converter
{
    public function convert(Unit $from, Unit $to, float $value = 1): float
    {
        if (!$from->canConvertTo($to)) {
            throw new \Exception("Cannot convert from [$from] to [$to]");
        }

        return $value * $this->toBase($from) / $this->toBase($to);
    }

    protected function toBase(Unit $unit): float
    {
        $value = 1;

        foreach ($unit->getParts() as $unitPart) {
            $value *= $unitPart->getRatio() ** $unitPart->getPower();
        }

        return $value;
    }

    /**
     * @return array{0: float, 1: Unit}
     */
    public function multiply(float $multiplicand, Unit $multiplicandUnit, float $multiplier, Unit $multiplierUnit): array
    {
        $productUnit = new Unit(...$multiplicandUnit->getParts(), ...$multiplierUnit->getParts());

        return [$multiplicand * $multiplier, $productUnit];
    }
}