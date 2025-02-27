<?php declare(strict_types=1);

use Conversion\Converter;
use Conversion\Dimension;
use Conversion\Unit;
use Conversion\UnitPart;
use PHPUnit\Framework\TestCase;

final class LengthTest extends TestCase
{
    protected Unit $meter;
    protected Unit $inch;
    protected Unit $foot;
    protected Unit $mile;
    protected Unit $chain;
    protected Unit $angstrom;
    protected Unit $mil;

    protected Converter $converter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->meter = new Unit(new UnitPart(1, Dimension::LENGTH, 1));
        $this->inch = new Unit(new UnitPart(0.0254, Dimension::LENGTH, 1));
        $this->foot = new Unit(new UnitPart(0.3048, Dimension::LENGTH, 1));
        $this->yard = new Unit(new UnitPart(0.9144, Dimension::LENGTH, 1));
        $this->mile = new Unit(new UnitPart(1609.344, Dimension::LENGTH, 1));
        $this->link = new Unit(new UnitPart(0.201168, Dimension::LENGTH, 1));
        $this->rod = new Unit(new UnitPart(5.0292, Dimension::LENGTH, 1));
        $this->chain = new Unit(new UnitPart(20.1168, Dimension::LENGTH, 1));
        $this->angstrom = new Unit(new UnitPart(1e-10, Dimension::LENGTH, 1));
        $this->mil = new Unit(new UnitPart(0.0000254, Dimension::LENGTH, 1));

        $this->converter = new Converter();
    }

    public function test_meter_inch(): void
    {
        $this->assertEqualsWithDelta(393.700787, $this->converter->convert($this->meter, $this->inch, 10), 0.000001);
        $this->assertEquals(0.254, $this->converter->convert($this->inch, $this->meter, 10));
    }

    public function test_meter_foot(): void
    {
        $this->assertEqualsWithDelta(32.808399, $this->converter->convert($this->meter, $this->foot, 10), 0.000001);
        $this->assertEquals(3.048, $this->converter->convert($this->foot, $this->meter, 10));
    }

    public function test_meter_mile(): void
    {
        $this->assertEqualsWithDelta(0.0062137, $this->converter->convert($this->meter, $this->mile, 10), 0.000001);
        $this->assertEquals(16_093.44, $this->converter->convert($this->mile, $this->meter, 10));
    }

    public function test_meter_chain(): void
    {
        $this->assertEqualsWithDelta(0.49709695, $this->converter->convert($this->meter, $this->chain, 10), 0.000001);
        $this->assertEquals(201.168, $this->converter->convert($this->chain, $this->meter, 10));
    }

    public function test_meter_angstrom(): void
    {
        $this->assertEquals(100_000_000_000, $this->converter->convert($this->meter, $this->angstrom, 10));
        $this->assertEquals(1e-9, $this->converter->convert($this->angstrom, $this->meter, 10));
    }

    public function test_meter_mil(): void
    {
        $this->assertEqualsWithDelta(393700.787, $this->converter->convert($this->meter, $this->mil, 10), 0.001);
        $this->assertEquals(0.000254, $this->converter->convert($this->mil, $this->meter, 10));
    }

    public function test_inch_meter(): void
    {
        $this->assertEquals(0.254, $this->converter->convert($this->inch, $this->meter, 10));
        $this->assertEqualsWithDelta(393.700787, $this->converter->convert($this->meter, $this->inch, 10), 0.000001);
    }

    public function test_foot_meter(): void
    {
        $this->assertEquals(3.048, $this->converter->convert($this->foot, $this->meter, 10));
        $this->assertEqualsWithDelta(32.808399, $this->converter->convert($this->meter, $this->foot, 10), 0.000001);
    }

    public function test_mile_meter(): void
    {
        $this->assertEquals(16_093.44, $this->converter->convert($this->mile, $this->meter, 10));
        $this->assertEqualsWithDelta(0.0062137, $this->converter->convert($this->meter, $this->mile, 10), 0.000001);
    }

    public function test_chain_meter(): void
    {
        $this->assertEquals(201.168, $this->converter->convert($this->chain, $this->meter, 10));
        $this->assertEqualsWithDelta(0.49709695378987, $this->converter->convert($this->meter, $this->chain, 10), 0.00000000000001);
    }

    public function test_angstrom_meter(): void
    {
        $this->assertEquals(1.0E-9, $this->converter->convert($this->angstrom, $this->meter, 10));
        $this->assertEquals(100_000_000_000, $this->converter->convert($this->meter, $this->angstrom, 10));
    }

    public function test_mil_meter(): void
    {
        $this->assertEqualsWithDelta(0.000254, $this->converter->convert($this->mil, $this->meter, 10), 0.000001);
        $this->assertEqualsWithDelta(393_700.78740157, $this->converter->convert($this->meter, $this->mil, 10), 0.00000001);
    }

    public function test_yard_meter(): void
    {
        $this->assertEqualsWithDelta(9.144, $this->converter->convert($this->yard, $this->meter, 10), 0.000001);
        $this->assertEquals(0.9144, $this->converter->convert($this->yard, $this->meter, 1));
    }

    public function test_link_meter(): void
    {
        $this->assertEqualsWithDelta(2.01168, $this->converter->convert($this->link, $this->meter, 10), 0.000001);
        $this->assertEquals(0.201168, $this->converter->convert($this->link, $this->meter, 1));
    }

    public function test_rod_meter(): void
    {
        $this->assertEqualsWithDelta(50.292, $this->converter->convert($this->rod, $this->meter, 10), 0.000001);
        $this->assertEquals(5.0292, $this->converter->convert($this->rod, $this->meter, 1));
    }
}
