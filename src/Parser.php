<?php declare(strict_types=1);

namespace Conversion;

class Parser
{
    public function __construct(protected Registry $registry)
    {

    }

    public function parse(string $input): Unit
    {
        $parts = explode('/', $input);
        $unitParts = array_map(fn($part) => self::parsePart($part), $parts);

        return new Unit(...$unitParts);
    }

    protected function parsePart(string $part): UnitPart
    {
        $unit = $this->registry->get($part);

        if ($unit === null) {
            throw new \Exception("Unknown unit: $part");
        }

        return $unit;
    }
}