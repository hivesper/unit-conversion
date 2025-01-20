<?php declare(strict_types=1);

namespace Conversion;

class Parser
{
    public function __construct(protected Registry $registry)
    {

    }

    // todo: this parsing is all wrong
    public function parse(string $input): Unit
    {
        $parts = explode('/', $input);
        $unitParts = array_map(fn($part) => $this->parsePart($part), $parts);

        return new Unit(...array_merge([], ...$unitParts));
    }

    protected function parsePart(string $part): array
    {
        $unit = $this->registry->get($part);

        if ($unit === null) {
            throw new \Exception("Unknown unit: $part");
        }

        return $unit;
    }
}