<?php declare(strict_types=1);

namespace Conversion;

enum Dimension
{
    case MASS;
    case LENGTH;
    case TIME;
    CASE CURRENT;
    case TEMPERATURE;
    case LUMINOUS_INTENSITY;
    case AMOUNT_OF_SUBSTANCE;
    case ANGLE;

    public function getUnitName(): string
    {
        return match ($this) {
            self::MASS => 'kilogram',
            self::LENGTH => 'meter',
            self::TIME => 'second',
            self::CURRENT => 'ampere',
            self::TEMPERATURE => 'kelvin',
            self::LUMINOUS_INTENSITY => 'candela',
            self::AMOUNT_OF_SUBSTANCE => 'mole',
            self::ANGLE => 'radian',
        };
    }
}