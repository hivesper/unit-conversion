<?php declare(strict_types=1);

use Conversion\Converter;
use Conversion\Dimension;
use Conversion\Parser;
use Conversion\Registry;
use Conversion\RegistryBuilder;
use Conversion\Unit;
use Conversion\UnitPart;
use PHPUnit\Framework\TestCase;

final class ConverterTest extends TestCase
{
    protected Registry $registry;
    protected Parser $parser;
    protected Converter $converter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registry = RegistryBuilder::build(new Registry());
        $this->parser = new Parser($this->registry);
        $this->converter = new Converter($this->registry);
    }

    public function test_converts_incompatible_throws()
    {
        $m = $this->parser->parse('meter');
        $m2 = $this->parser->parse('meter^2');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Cannot convert from [$m] to [$m2]");

        $this->converter->convert($m, $m2);
    }

    public function test_simple_convert()
    {
        $km = $this->parser->parse('kilometer');
        $meter = $this->parser->parse('meter');
        $hm = $this->parser->parse('hectometer');

        $this->assertEquals(2000, $this->converter->convert($km, $meter, 2));
        $this->assertEquals(0.002, $this->converter->convert($meter, $km, 2));

        $this->assertEquals(200, $this->converter->convert($hm, $meter, 2));
        $this->assertEquals(0.2, $this->converter->convert($hm, $km, 2));
    }

    public function test_area_convert()
    {
        $km2 = $this->parser->parse('kilometer^2');
        $m2 = $this->parser->parse('meter^2');

        $this->assertEquals(2, $this->converter->convert($m2, $km2, 2_000_000));
        $this->assertEquals(2_000_000, $this->converter->convert($km2, $m2, 2));
    }

    public function test_convert_flattens_dimensions()
    {
        $km2 = $this->parser->parse('kilometer^2');
        $m2 = $this->parser->parse('meter^2');
        $meterMeter = new Unit(
            new UnitPart(1, Dimension::LENGTH, 1),
            new UnitPart(1, Dimension::LENGTH, 1),
        );
        $this->assertEquals(2, $this->converter->convert($m2, $meterMeter, 2));
        $this->assertEquals(2, $this->converter->convert($meterMeter, $km2, 2_000_000));
    }

    public function test_time_convert()
    {
        $hour = $this->parser->parse('hour');
        $minute = $this->parser->parse('minute');
        $second = $this->parser->parse('second');

        $this->assertEquals(120, $this->converter->convert($hour, $minute,2));
        $this->assertEquals(2, $this->converter->convert($minute, $hour, 120));
        $this->assertEquals(120, $this->converter->convert($minute, $second, 2));
        $this->assertEquals(7200, $this->converter->convert($hour, $second, 2));
    }

    public function test_volume_convert()
    {
        $liter = $this->parser->parse('liter');
        $dm3 = $this->parser->parse('decimeter^3');
        $m3 = $this->parser->parse('meter^3');

        $this->assertEqualsWithDelta(2, $this->converter->convert($dm3, $liter, 2), 0.000001);
        $this->assertEqualsWithDelta(2000, $this->converter->convert($m3, $liter, 2), 0.000001);
    }

    public function test_compound_convert()
    {
        $meterPerSecond = new Unit(
            new UnitPart( 1, Dimension::LENGTH, 1),
            new UnitPart( 1, Dimension::TIME, -1),
        );
        $kmPerHour = new Unit(
            new UnitPart( 1000, Dimension::LENGTH, 1),
            new UnitPart( 3600, Dimension::TIME, -1),
        );

        $this->assertEqualsWithDelta(10, $this->converter->convert($kmPerHour, $meterPerSecond, 36), 0.000001);
        $this->assertEqualsWithDelta(36, $this->converter->convert($meterPerSecond, $kmPerHour, 10), 0.000001);
    }

    public function test_with_ratios()
    {
        $kg = $this->parser->parse('kilogram');
        $m3 = $this->parser->parse('meter^3');
        $liter = $this->parser->parse('liter');

        $converterWithDensity = $this->converter->withRatios(["$kg/$m3" => 1000]);

        $this->assertEquals(2, $converterWithDensity->convert($kg, $m3, 2000));
        $this->assertEquals(2, $converterWithDensity->convert($kg, $liter, 2));
    }
}
