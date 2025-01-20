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
}