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

        if ($from->isCompound()) {
            return $this->convertCompoundUnit($from, $to, $value);
        }

        return $this->convertPart($from->getPart(0), $to->getPart(0), $value);
    }

    protected function convertPart(UnitPart $from, UnitPart $to, float $value = 1): float
    {
        $ratio = $this->getRatio($from, $to);

        if ($ratio === null) {
            return $value * $from->getRatio() / $to->getRatio();
        }

        $baseFrom = $this->registry->get($from->getType()->value);
        $baseTo = $this->registry->get($to->getType()->value);

        $value = $this->convertPart($from, $baseFrom, $value);
        $value = $this->convertPart($baseTo, $to, $value);

        return $value / $ratio;
    }

    protected function convertCompoundUnit(Unit $from, Unit $to, float $value = 1): float
    {
        $ratio = $this->convertPart($from->getPart(0), $to->getPart(0));

        for ($i = 1; $i < count($from->getParts()); $i++) {
            $ratio /= $this->convertPart($from->getPart($i), $to->getPart($i));
        }

        return $value / $ratio;
    }

    public function canConvert(Unit $from, Unit $to): bool
    {
        foreach ($from->getParts() as $index => $fromPart) {
            $toPart = $to->getPart($index);

            if ($fromPart->getType() === $toPart->getType()) {
                continue;
            }

            if ($this->getRatio($fromPart, $toPart) !== null) {
                continue;
            }

            return false;
        }

        return true;
    }

    protected function getRatio(UnitPart $from, UnitPart $to): ?float
    {
        return $this->ratios["{$from->getType()->value}/{$to->getType()->value}"] ?? null;
    }
}