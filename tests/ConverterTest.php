<?php declare(strict_types=1);

Use Vesper\UnitConversion\Converter;
Use Vesper\UnitConversion\Dimension;
Use Vesper\UnitConversion\Unit;
Use Vesper\UnitConversion\UnitPart;
use PHPUnit\Framework\TestCase;

final class ConverterTest extends TestCase
{
    protected Converter $converter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->converter = new Converter();
    }

    public function test_converts_incompatible_throws()
    {
        $m = new Unit(
            new UnitPart(1, Dimension::LENGTH, 1),
        );
        $m2 = new Unit(
            new UnitPart(1, Dimension::LENGTH, 1),
            new UnitPart(1, Dimension::LENGTH, 1),
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Cannot convert from [$m] to [$m2]");

        $this->converter->convert($m, $m2);
    }

    public function test_convert_flattens_dimensions()
    {
        $km2 = new Unit(
            new UnitPart(1000, Dimension::LENGTH, 2),
        );
        $m2 = new Unit(
            new UnitPart(1, Dimension::LENGTH, 2),
        );
        $meterMeter = new Unit(
            new UnitPart(1, Dimension::LENGTH, 1),
            new UnitPart(1, Dimension::LENGTH, 1),
        );
        $this->assertEquals(2, $this->converter->convert($m2, $meterMeter, 2));
        $this->assertEquals(2, $this->converter->convert($meterMeter, $km2, 2_000_000));
    }

    public function test_compound_convert()
    {
        $meterPerSecond = new Unit(
            new UnitPart(1, Dimension::LENGTH, 1),
            new UnitPart(1, Dimension::TIME, -1),
        );
        $kmPerHour = new Unit(
            new UnitPart(1000, Dimension::LENGTH, 1),
            new UnitPart(3600, Dimension::TIME, -1),
        );

        $this->assertEqualsWithDelta(10, $this->converter->convert($kmPerHour, $meterPerSecond, 36), 0.000001);
        $this->assertEqualsWithDelta(36, $this->converter->convert($meterPerSecond, $kmPerHour, 10), 0.000001);
    }

    public function test_multiply()
    {
        $gPerCm3 = new Unit(
            new UnitPart(0.001, Dimension::MASS, 1),
            new UnitPart(0.01, Dimension::LENGTH, -3),
        );
        $kgPerCm3 = new Unit(
            new UnitPart(1, Dimension::MASS, 1),
            new UnitPart(0.01, Dimension::LENGTH, -3),
        );
        $cm3 = new Unit(
            new UnitPart(0.01, Dimension::LENGTH, 3),
        );

        [$amount, $g] = $this->converter->multiply(240, $cm3, 9, $gPerCm3);
        [$amount2, $kg] = $this->converter->multiply(240, $cm3, 9, $kgPerCm3);

        $this->assertEquals(2160, $amount);
        $this->assertFalse($g->isCompound());
        $this->assertEqualsWithDelta(
            0.001,
            array_product(array_map(fn($part) => $part->getRatio() ** $part->getPower(), $g->getParts())),
            0.000001
        );

        $this->assertEquals(2160, $amount2);
        $this->assertFalse($kg->isCompound());
        $this->assertEqualsWithDelta(
            1,
            array_product(array_map(fn($part) => $part->getRatio() ** $part->getPower(), $kg->getParts())),
            0.000001
        );
    }

    public function test_divide()
    {
        $g = new Unit(
            new UnitPart(0.001, Dimension::MASS, 1),
        );
        $cm3 = new Unit(
            new UnitPart(0.01, Dimension::LENGTH, 3),
        );

        [$amount, $gPerCm3] = $this->converter->divide(30, $g, 3, $cm3);

        $this->assertEquals(10, $amount);
        $this->assertTrue($gPerCm3->isCompound());
        $this->assertEqualsWithDelta(
            1000,
            array_product(array_map(fn($part) => $part->getRatio() ** $part->getPower(), $gPerCm3->getParts())),
            0.000001
        );

    }
}
