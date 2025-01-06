<?php declare(strict_types=1);

use Conversion\Type;
use Conversion\Unit;
use Conversion\UnitPart;
use PHPUnit\Framework\TestCase;

final class ConverterTest extends TestCase
{
    public function test_converts_incompatible_throws()
    {
        $a = new Unit(
            new UnitPart('meter', Type::LENGTH, 1),
        );

        $b = new Unit(
            new UnitPart('second', Type::TIME, 1),
        );

        $this->expectException(InvalidArgumentException::class);

        Converter::convert($a, $b);
    }
}
