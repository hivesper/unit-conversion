<?php declare(strict_types=1);

Use Vesper\Conversion\Dimension;
Use Vesper\Conversion\UnitPart;
use PHPUnit\Framework\TestCase;

final class UnitPartTest extends TestCase
{
    public function test_stringifies()
    {
        $this->assertEquals('meter^-1', (string) new UnitPart(1, Dimension::LENGTH, -1));
        $this->assertEquals('meter^0', (string) new UnitPart(1, Dimension::LENGTH, 0));
        $this->assertEquals('meter', (string) new UnitPart(1, Dimension::LENGTH, 1));
        $this->assertEquals('meter^2', (string) new UnitPart(1, Dimension::LENGTH, 2));
        $this->assertEquals('meter^10', (string) new UnitPart(1, Dimension::LENGTH, 10));
    }

    public function test_get_dimensions()
    {
        $unitPart = new UnitPart(1, Dimension::LENGTH, 2);

        $this->assertEquals(Dimension::LENGTH, $unitPart->getDimension());
        $this->assertEquals(2, $unitPart->getPower());
    }

    public function test_invert()
    {
        $unitPart = (new UnitPart(1, Dimension::LENGTH, 2))->invert();

        $this->assertEquals(1, $unitPart->getRatio());
        $this->assertEquals(Dimension::LENGTH, $unitPart->getDimension());
        $this->assertEquals(-2, $unitPart->getPower());
    }
}
