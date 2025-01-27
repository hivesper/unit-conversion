<?php declare(strict_types=1);

namespace Conversion;

class RatioBag {
    protected array $ratios = [];

    public function add(Unit $from, Unit $to, callable $convert): self {
        $this->ratios[] = [
            'from' => $from,
            'to' => $to,
            'convert' => $convert
        ];

        return $this;
    }
    
    public function get(Unit $from, Unit $to): ?array
    {
        foreach ($this->ratios as $ratio) {
            if ($from->canConvertTo($ratio['from']) && $to->canConvertTo($ratio['to'])) {
                return $ratio;
            }
        }

        return null;
    }
}