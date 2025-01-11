<?php declare(strict_types=1);

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

    public function isCompound(): bool
    {
        return count($this->parts) > 1;
    }

    public function __toString(): string
    {
        return implode('/', $this->parts);
    }
}