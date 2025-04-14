<?php declare(strict_types=1);

namespace Vesper\UnitConversion;

use Vesper\UnitConversion\Exceptions\InvalidUnitException;

class Parser
{
    public function __construct(protected Registry $registry)
    {

    }

    public function parse(string $input): Unit
    {
        $unit = $this->registry->get($input);
        if ($unit) {
            return $unit;
        }

        $tokens = $this->tokenize($input);
        $parts = [];

        foreach ($tokens as $i => $token) {
            $prevToken = $tokens[$i - 1] ?? null;

            if ($token['type'] !== 'unit') {
                continue;
            }

            $unit = $this->registry->get($token['value']);

            if ($unit === null) {
                throw new InvalidUnitException("Unknown unit: {$token['value']}");
            }

            $powerSign = $prevToken && $prevToken['type'] === 'operator' && $prevToken['value'] === '/'
                ? -1
                : 1;
            $power = $token['power'] * $powerSign;

            $parts[] = array_map(function (UnitPart|FactorUnitPart $part) use ($power) {
                if ($part instanceof FactorUnitPart) {
                    return new FactorUnitPart($part->getRatio());
                }

                return new UnitPart(
                    $part->getRatio(),
                    $part->getDimension(),
                    $part->getPower() * $power,
                );
            }, $unit->getParts());
        }

        return new Unit(...array_merge([], ...$parts));
    }
    protected function tokenize(string $input): array
    {
        $matches = [];
        $tokens = [];

        // Handle cases like "watt-hour" or "watt-hour/kg" with different regex logic:
        // - Dash-separated units (e.g., "watt-hour") should be treated as a single unit.
        // - For inputs with division operators (e.g., "watt-hour/kg"), units separated by "/"
        //   should be processed individually while still supporting dash-separated units.
        $allowDash = str_contains($input, '/') || str_contains($input, '*');

        $regex = $allowDash
            ? '/(?P<unit>[\w-]+)(?:\^(?P<power>-?\d))?|(?P<operator>[*\/])/'
            : '/(?P<unit>\w+)(?:\^(?P<power>-?\d))?|(?P<operator>[*\/])/';

        preg_match_all(
            $regex,
            preg_replace('/\s+/', '', $input),
            $matches,
            PREG_SET_ORDER
        );

        foreach ($matches as $match) {
            if ($match['unit']) {
                // If the unit contains a dash, check if it's a registered unit as a whole
                if (str_contains($match['unit'], '-') && $this->registry->get($match['unit']) !== null) {
                    // If the compound unit (e.g., "watt-hour") is in the registry, treat it as a single unit
                    $tokens[] = [
                        'type' => 'unit',
                        'value' => $match['unit'],
                        'power' => (int)($match['power'] ?? 1),
                    ];
                } else {
                    // Otherwise, split the dash-separated units into individual tokens (e.g., "gram-liter" becomes "gram" and "liter")
                    $units = explode('-', $match['unit']);
                    foreach ($units as $unit) {
                        $tokens[] = [
                            'type' => 'unit',
                            'value' => $unit,
                            'power' => (int)($match['power'] ?? 1),
                        ];
                    }
                }
            } else if ($match['operator']) {
                $tokens[] = [
                    'type' => 'operator',
                    'value' => $match['operator'],
                ];
            }
        }

        return $tokens;
    }
}