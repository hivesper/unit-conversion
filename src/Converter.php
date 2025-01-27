<?php declare(strict_types=1);

namespace Conversion;

class Converter
{
    public function __construct(protected ?RatioBag $ratios = null)
    {

    }

    public function withRatios(RatioBag $ratios): self
    {
        return new self($ratios);
    }

    public function withRatio(Unit $from, Unit $to, callable $convert): self
    {
        $ratios = is_null($this->ratios) ? new RatioBag() : clone $this->ratios;
        $ratios->add($from, $to, $convert);

        return $this->withRatios($ratios);
    }

    public function convert(Unit $from, Unit $to, float $value = 1): float
    {
        if (!$this->canConvert($from, $to)) {
            throw new \Exception("Cannot convert from [$from] to [$to]");
        }

        if ($convert = $this->ratios?->get($from, $to)) {
            return $this->convertWithRatio($from, $to, $value, $convert);
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

    protected function convertWithRatio(Unit $from, Unit $to, float $value, array $ratio): float
    {
        $value * $this->toBase($from) / $this->toBase($ratio['from']);

        $value = $ratio['convert']($value);

        return $value * $this->toBase($ratio['to']) / $this->toBase($to);
    }

    public function canConvert(Unit $from, Unit $to): bool
    {
        if ($this->ratios?->get($from, $to) !== null) {
            return true;
        }

        return $from->canConvertTo($to);
    }
}