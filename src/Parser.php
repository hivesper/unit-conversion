<?php declare(strict_types=1);

namespace Conversion;

class Parser
{
    public function __construct(protected Registry $registry)
    {

    }

    public function parse(string $input): Unit
    {
        $tokens = $this->tokenize($input);
        $parts = [];

        foreach ($tokens as $i => $token) {
            $prevToken = $tokens[$i - 1] ?? null;

            if ($token['type'] === 'unit') {
                $unitParts = $this->registry->get($token['value']);

                if ($unitParts === null) {
                    throw new \Exception("Unknown unit: {$token['value']}");
                }

                $powerSign = $prevToken && $prevToken['type'] === 'operator' && $prevToken['value'] === '/'
                    ? -1
                    : 1;

                $parts[] = array_map(fn (UnitPart $part) => new UnitPart(
                    $part->getRatio(),
                    $part->getDimension(),
                    ($token['power'] ** $part->getPower()) * $powerSign,
                ), $unitParts);
            }
        }

        return new Unit(...array_merge([], ...$parts));
    }

    protected function tokenize(string $input): array
    {
        $matches = [];
        $tokens = [];

        preg_match_all(
            '/(?P<unit>\w+)(?:\^(?P<power>-?\d))?|(?P<operator>[*\/])/',
            preg_replace('/\s+/', '', $input),
            $matches,
            PREG_SET_ORDER
        );


        foreach ($matches as $match) {
            if ($match['unit']) {
                $tokens[] = [
                    'type' => 'unit',
                    'value' => $match['unit'],
                    'power' => (int)($match['power'] ?? 1),
                ];
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