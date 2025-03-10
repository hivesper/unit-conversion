<?php declare(strict_types=1);

Use Vesper\UnitConversion\FactorUnitPart;
use PHPUnit\Framework\TestCase;

final class FactorUnitPartTest extends TestCase
{
    public function test_constructs()
    {
        $factor = new FactorUnitPart(100);

        $this->assertNull($factor->getDimension());
        $this->assertEquals(100, $factor->getRatio());
        $this->assertEquals(1, $factor->getPower());
    }

    public function test_stringifies()
    {
        $this->assertEquals('100', (string)new FactorUnitPart(100));
    }
}
