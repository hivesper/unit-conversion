<?php declare(strict_types=1);

namespace Conversion;

readonly class Unit
{
    protected array $parts;

    protected float $ratio;

    public function __construct(UnitPart ...$parts)
    {
        $this->parts = $parts;

        $this->ratio = array_reduce(
            $parts,
            fn (float $carry, UnitPart $part) => $carry * $part->getRatio() ** $part->getPower(),
            1
        );
    }

    public function getRatio(): float
    {
        return $this->ratio;
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
        return count(array_filter($this->getDimensions())) > 1;
    }

    public function getDimensions(): array
    {
        $dimensions = [
            Dimension::MASS->name => 0,
            Dimension::LENGTH->name => 0,
            Dimension::TIME->name => 0,
            Dimension::CURRENT->name => 0,
            Dimension::TEMPERATURE->name => 0,
            Dimension::LUMINOUS_INTENSITY->name => 0,
            Dimension::AMOUNT_OF_SUBSTANCE->name => 0,
            Dimension::ANGLE->name => 0,
        ];

        foreach ($this->getParts() as $part) {
            $dimension = $part->getDimension();

            if (is_null($dimension)) {
                continue;
            }

            $dimensions[$dimension->name] += $part->getPower();
        }

        return $dimensions;
    }

    public function canConvertTo(Unit $unit): bool
    {
        return $this->getDimensions() === $unit->getDimensions();
    }

    public function __toString(): string
    {
        return $this->format();
    }

    public function format(): string
    {
        $dimensions = array_filter($this->getDimensions());

        $parts = array_map(function(string $dimension, int $power) {
            $name = constant(Dimension::class . '::' . $dimension)->getUnitName();
            return $power === 1 ? $name : "$name^$power";
        }, array_keys($dimensions), $dimensions);

        return implode('*', $parts);
    }

    public function multiply(Unit $other): self
    {
        return new self(...$this->getParts(), ...$other->getParts());
    }

    public function divide(Unit $other): self
    {
        return new self(...$this->getParts(), ...$other->invert()->getParts());
    }

    public function invert(): self
    {
        return new self(...array_map(fn (UnitPart $part) => $part->invert(), $this->getParts()));
    }
}