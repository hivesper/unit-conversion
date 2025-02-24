<?php declare(strict_types=1);

namespace Conversion;

class UnitPart
{
    public function __construct(
        protected float $ratio,
        protected Dimension $dimension,
        protected int $power,
        protected float $offset = 0
    ) {

    }

    public function getName(): string
    {
        return $this->dimension->getUnitName();
    }

    public function getRatio(): float
    {
        return $this->ratio;
    }

    public function getDimension(): Dimension
    {
        return $this->dimension;
    }

    public function getPower(): int
    {
        return $this->power;
    }

    public function getOffset(): float
    {
        return $this->offset;
    }

    public function __toString(): string
    {
        return $this->format();
    }

    public function format(): string
    {
        $name = $this->getName();
        $power = $this->getPower();

        if ($power === 1) {
            return $name;
        }

        return "$name^$this->power";
    }

    public function invert(): self
    {
        return new self($this->ratio, $this->dimension, -$this->power);
    }
}