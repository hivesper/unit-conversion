<?php

namespace Conversion;

class UnitPart
{
    public function __construct(protected string $name, protected Type $type, protected float $ratio)
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

    public function getRatio(): float
    {
        return $this->ratio;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}