<?php declare(strict_types=1);

namespace Conversion;

class UnitPart
{
    public function __construct(protected string $name, protected float $ratio, protected Dimension $dimension, protected int $power)
    {

    }

    public function getName(): string
    {
        return $this->name;
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

    public function __toString(): string
    {
        return $this->getName();
    }
}