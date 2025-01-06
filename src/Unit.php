<?php

class Unit
{
    protected array $parts;

    public function __construct(UnitPart ...$parts)
    {
        $this->parts = $parts;
    }

    public function getParts(): array
    {
        return $this->parts;
    }

    public function canConvertTo(Unit $part): bool
    {
        $thisParts = $this->getParts();
        $unitParts = $part->getParts();

        if (count($thisParts) !== count($unitParts)) {
            return false;
        }

        foreach ($thisParts as $index => $part) {
            if (!$part->canConvertTo($unitParts[$index])) {
                return false;
            }
        }

        return true;
    }

    public function __toString(): string
    {
        return implode('/', $this->parts);
    }
}