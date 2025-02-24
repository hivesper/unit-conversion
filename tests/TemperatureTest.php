<?php declare(strict_types=1);

use Conversion\Converter;
use Conversion\Dimension;
use Conversion\Unit;
use Conversion\UnitPart;
use PHPUnit\Framework\TestCase;

final class TemperatureTest extends TestCase
{
    protected Unit $kelvin;
    protected Unit $celsius;
    protected Unit $fahrenheit;
    
    protected Converter $converter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->kelvin = new Unit(new UnitPart(1, Dimension::TEMPERATURE, 1));
        $this->celsius = new Unit(new UnitPart(1, Dimension::TEMPERATURE, 1, 273.15));
        $this->fahrenheit = new Unit(new UnitPart(5 / 9, Dimension::TEMPERATURE, 1, 459.67));
        
        $this->converter = new Converter();
    }

    public function test_kelvin_celsius(): void
    {
        $this->assertEquals(-263.15, $this->converter->convert($this->kelvin, $this->celsius, 10));
        $this->assertEquals(283.15, $this->converter->convert($this->celsius, $this->kelvin, 10));
    }

    public function test_kelvin_fahrenheit(): void
    {
        $this->assertEqualsWithDelta(-459.67, $this->converter->convert($this->kelvin, $this->fahrenheit, 0), 0.000001);
        $this->assertEqualsWithDelta(-279.67, $this->converter->convert($this->kelvin, $this->fahrenheit, 100), 0.000001);
        $this->assertEqualsWithDelta(0, $this->converter->convert($this->kelvin, $this->fahrenheit, 255.372222), 0.000001);

        $this->assertEqualsWithDelta(255.372222, $this->converter->convert($this->fahrenheit, $this->kelvin, 0), 0.000001);
        $this->assertEqualsWithDelta(310.927778, $this->converter->convert($this->fahrenheit, $this->kelvin, 100), 0.000001);
    }

    public function test_compound_convert()
    {
        $mk = new Unit(
            new UnitPart(1, Dimension::LENGTH, 1),
            ...$this->kelvin->invert()->getParts()
        );
        $mc = new Unit(
            new UnitPart(1, Dimension::LENGTH, 1),
            ...$this->celsius->invert()->getParts()
        );
        $mf = new Unit(
            new UnitPart(1, Dimension::LENGTH, 1),
            ...$this->fahrenheit->invert()->getParts()
        );

        $this->assertEqualsWithDelta(10, $this->converter->convert($mk, $mc, 10), 0.000001);
        $this->assertEqualsWithDelta(2.7777777777778 , $this->converter->convert($mc, $mf, 5), 0.000001);
    }
}
