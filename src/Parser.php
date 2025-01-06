<?php

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
            throw new \InvalidArgumentException("Unknown unit: $part");
        }

        return new UnitPart($unit['name'], $unit['type'], $unit['scale']);
    }
}