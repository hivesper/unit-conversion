<?php declare(strict_types=1);

namespace Conversion;

class Converter
{
    public function __construct(protected Registry $registry, protected array $ratios = [])
    {

    }

    public function withRatios(array $ratios): self
    {
        return new self($this->registry, $ratios);
    }

    public function convert(Unit $from, Unit $to, float $value = 1): float
    {
        if (!$this->canConvert($from, $to)) {
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

    public function canConvert(Unit $from, Unit $to): bool
    {
        foreach ($from->getDimensions() as $dimension => $ratio) {
            if ($to->getDimensions()[$dimension] !== $ratio) {
                return false;
            }

            // todo: re-add conversion ratios
        }

        return true;
    }

    protected function getRatio(UnitPart $from, UnitPart $to): ?float
    {
        return null;
//        return $this->ratios["{$from->getType()->value}/{$to->getType()->value}"] ?? null;
    }
}