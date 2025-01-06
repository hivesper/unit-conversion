<?php declare(strict_types=1);

use Conversion\Type;
use Conversion\Unit;
use Conversion\UnitPart;
use PHPUnit\Framework\TestCase;

final class UnitTest extends TestCase
{
    public function test_stringifies()
    {
        $unitPart = new Unit(
            new UnitPart('meter', Type::LENGTH, 1),
            new UnitPart('second', Type::TIME, 1)
        );

        $this->assertEquals('meter/second', (string) $unitPart);
    }
}
