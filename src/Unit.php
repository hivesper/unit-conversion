<?php

namespace Conversion;

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

    public function getPart(int $index): UnitPart
    {
        return $this->parts[$index];
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

    public function isCompound(): bool
    {
        return count($this->parts) > 1;
    }

    public function __toString(): string
    {
        return implode('/', $this->parts);
    }
}