<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Vesper\UnitConversion\FactorUnitPart;

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

    public function test_inverts()
    {
        $factor = new FactorUnitPart(100);

        $factorInverted = $factor->invert();

        $this->assertEquals(FactorUnitPart::class, get_class($factorInverted));
        $this->assertNull($factorInverted->getDimension());
        $this->assertEquals(0.01, $factorInverted->getRatio());
        $this->assertEquals(1, $factorInverted->getPower());
    }
}
