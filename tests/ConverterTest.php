<?php declare(strict_types=1);

use Conversion\Converter;
use Conversion\Dimension;
use Conversion\Unit;
use Conversion\UnitPart;
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

    public function test_simple_convert()
    {
        $km = new Unit(
            new UnitPart(1000, Dimension::LENGTH, 1),
        );
        $m = new Unit(
            new UnitPart(1, Dimension::LENGTH, 1),
        );
        $hm = new Unit(
            new UnitPart(100, Dimension::LENGTH, 1),
        );

        $this->assertEquals(2000, $this->converter->convert($km, $m, 2));
        $this->assertEquals(0.002, $this->converter->convert($m, $km, 2));

        $this->assertEquals(200, $this->converter->convert($hm, $m, 2));
        $this->assertEquals(0.2, $this->converter->convert($hm, $km, 2));
    }

    public function test_area_convert()
    {
        $km2 = new Unit(
            new UnitPart(1000, Dimension::LENGTH, 2),
        );
        $m2 = new Unit(
            new UnitPart(1, Dimension::LENGTH, 2),
        );
        $cm2 = new Unit(
            new UnitPart(0.01, Dimension::LENGTH, 2),
        );

        $this->assertEquals(2, $this->converter->convert($m2, $km2, 2_000_000));
        $this->assertEquals(2_000_000, $this->converter->convert($km2, $m2, 2));

        $this->assertEquals(20_000, $this->converter->convert($m2, $cm2, 2));
        $this->assertEquals(0.0002, $this->converter->convert($cm2, $m2, 2));
    }

    public function test_volume_convert()
    {
        $km3 = new Unit(
            new UnitPart(1000, Dimension::LENGTH, 3),
        );
        $m3 = new Unit(
            new UnitPart(1, Dimension::LENGTH, 3),
        );
        $cm3 = new Unit(
            new UnitPart(0.01, Dimension::LENGTH, 3),
        );

        $this->assertEquals(2, $this->converter->convert($m3, $km3, 2_000_000_000));
        $this->assertEquals(2_000_000_000, $this->converter->convert($km3, $m3, 2));

        $this->assertEqualsWithDelta(2_000_000, $this->converter->convert($m3, $cm3, 2), 0.000001);
        $this->assertEqualsWithDelta(0.000002, $this->converter->convert($cm3, $m3, 2), 0.000001);
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

    public function test_time_convert()
    {
        $hour = new Unit(
            new UnitPart(3600, Dimension::TIME, 1),
        );
        $minute = new Unit(
            new UnitPart(60, Dimension::TIME, 1),
        );
        $second = new Unit(
            new UnitPart(1, Dimension::TIME, 1),
        );

        $this->assertEquals(120, $this->converter->convert($hour, $minute, 2));
        $this->assertEquals(2, $this->converter->convert($minute, $hour, 120));
        $this->assertEquals(120, $this->converter->convert($minute, $second, 2));
        $this->assertEquals(7200, $this->converter->convert($hour, $second, 2));
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

    public function test_with_ratios()
    {
        $kg = new Unit(
            new UnitPart(1, Dimension::MASS, 1),
        );
        $m3 = new Unit(
            new UnitPart(1, Dimension::LENGTH, 3),
        );

        $converterWithDensity = $this->converter->withRatio($kg, $m3, fn($value) => $value / 1000);

        $this->assertEquals(2, $converterWithDensity->convert($kg, $m3, 2000));
    }

    public function test_with_ratios_related()
    {
        $kg = new Unit(
            new UnitPart(1, Dimension::MASS, 1),
        );
        $m3 = new Unit(
            new UnitPart(1, Dimension::LENGTH, 3),
        );
        $liter = new Unit(
            new UnitPart(0.001, Dimension::LENGTH, 3),
        );

        $kgToM3 = fn ($value) => $value / 1000;

        $converterWithDensity = $this->converter->withRatio($kg, $m3, $kgToM3);

        $this->assertEquals(2000000, $converterWithDensity->convert($kg, $liter, 2));
    }
}
