<?php declare(strict_types=1);

namespace Conversion;

class UnitPart
{
    public function __construct(protected float $ratio, protected Dimension $dimension, protected int $power)
    {

    }

    public function getName(): string
    {
        return match ($this->dimension) {
            Dimension::MASS => 'kilogram',
            Dimension::LENGTH => 'meter',
            Dimension::TIME => 'second',
            Dimension::CURRENT => 'ampere',
            Dimension::TEMPERATURE => 'kelvin',
            Dimension::LUMINOUS_INTENSITY => 'candela',
            Dimension::AMOUNT_OF_SUBSTANCE => 'mole',
            Dimension::ANGLE => 'radian',
        };
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
}