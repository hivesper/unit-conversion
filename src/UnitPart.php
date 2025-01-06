<?php

namespace Conversion;

readonly class UnitPart
{
    public function __construct(protected string $name, protected Type $type, protected float $scale)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function getScale(): float
    {
        return $this->scale;
    }

    public function canConvertTo(UnitPart $unit): bool
    {
        return $this->getType() === $unit->getType();
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}