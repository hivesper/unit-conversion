<?php declare(strict_types=1);

use Conversion\Dimension;
use Conversion\UnitPart;
use PHPUnit\Framework\TestCase;

final class UnitPartTest extends TestCase
{
    public function test_stringifies()
    {
        $unitPart = new UnitPart('unknown', 1, Dimension::LENGTH, 1);

        $this->assertEquals('unknown', (string) $unitPart);
    }

    public function test_get_dimensions()
    {
        $unitPart = new UnitPart('meter^2', 1, Dimension::LENGTH, 2);

        $this->assertEquals(Dimension::LENGTH, $unitPart->getDimension());
        $this->assertEquals(2, $unitPart->getPower());
    }
}
