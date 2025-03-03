<?php declare(strict_types=1);

namespace Conversion;

readonly class FactorUnitPart extends UnitPart
{
    public function __construct(float $ratio) {
        parent::__construct($ratio, null, 1);
    }

    #[\Override]
    public function format(): string
    {
        return "{$this->getRatio()}";
    }
}