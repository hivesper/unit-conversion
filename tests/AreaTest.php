<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Vesper\UnitConversion\Converter;
use Vesper\UnitConversion\Dimension;
use Vesper\UnitConversion\Unit;
use Vesper\UnitConversion\UnitPart;

final class AreaTest extends TestCase
{
    protected Unit $squareMeter;
    protected Unit $squareInch;
    protected Unit $squareFoot;
    protected Unit $squareYard;
    protected Unit $squareMile;
    protected Unit $squareRod;
    protected Unit $squareChain;
    protected Unit $squareMil;
    protected Unit $acre;
    protected Unit $hectare;

    protected Converter $converter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->squareMeter = new Unit(new UnitPart(1, Dimension::LENGTH, 2));
        $this->squareInch = new Unit(new UnitPart(sqrt(0.00064516), Dimension::LENGTH, 2));
        $this->squareFoot = new Unit(new UnitPart(sqrt(0.09290304), Dimension::LENGTH, 2));
        $this->squareYard = new Unit(new UnitPart(sqrt(0.83612736), Dimension::LENGTH, 2));
        $this->squareMile = new Unit(new UnitPart(sqrt(2589988.110336), Dimension::LENGTH, 2));
        $this->squareRod = new Unit(new UnitPart(sqrt(25.29295), Dimension::LENGTH, 2));
        $this->squareChain = new Unit(new UnitPart(sqrt(404.6873), Dimension::LENGTH, 2));
        $this->squareMil = new Unit(new UnitPart(sqrt(6.4516e-10), Dimension::LENGTH, 2));
        $this->acre = new Unit(new UnitPart(sqrt(4046.86), Dimension::LENGTH, 2));
        $this->hectare = new Unit(new UnitPart(100, Dimension::LENGTH, 2));

        $this->converter = new Converter();
    }

    public function test_squareMeter_squareInch(): void
    {
        $this->assertEqualsWithDelta(1550.0031000062, $this->converter->convert($this->squareMeter, $this->squareInch, 1), 0.0000001);
        $this->assertEqualsWithDelta(0.00064516, $this->converter->convert($this->squareInch, $this->squareMeter, 1), 0.0000001);
    }

    public function test_squareMeter_squareFoot(): void
    {
        $this->assertEqualsWithDelta(10.76391041671, $this->converter->convert($this->squareMeter, $this->squareFoot, 1), 0.000001);
        $this->assertEqualsWithDelta(0.09290304, $this->converter->convert($this->squareFoot, $this->squareMeter, 1), 0.000000001);
    }

    public function test_squareMeter_squareYard(): void
    {
        $this->assertEqualsWithDelta(1.1959900463011, $this->converter->convert($this->squareMeter, $this->squareYard, 1), 0.0000001);
        $this->assertEqualsWithDelta(0.83612736, $this->converter->convert($this->squareYard, $this->squareMeter, 1), 0.000000001);
    }

    public function test_squareMeter_squareMile(): void
    {
        $this->assertEqualsWithDelta(1.4909e-13, $this->converter->convert($this->squareMeter, $this->squareMile, 1), 0.0001);
        $this->assertEqualsWithDelta(2_589_988.110336, $this->converter->convert($this->squareMile, $this->squareMeter, 1), 0.1);
    }

    public function test_squareMeter_squareRod(): void
    {
        $this->assertEqualsWithDelta(0.039536, $this->converter->convert($this->squareMeter, $this->squareRod, 1), 0.0001);
        $this->assertEqualsWithDelta(25.29295, $this->converter->convert($this->squareRod, $this->squareMeter, 1), 0.000000001);
    }

    public function test_squareMeter_squareChain(): void
    {
        $this->assertEqualsWithDelta(0.0024710, $this->converter->convert($this->squareMeter, $this->squareChain, 1), 0.0001);
        $this->assertEqualsWithDelta(404.6873, $this->converter->convert($this->squareChain, $this->squareMeter, 1), 0.00000001);
    }

    public function test_squareMeter_squareMil(): void
    {
        $this->assertEqualsWithDelta(1_550_003_100.0062, $this->converter->convert($this->squareMeter, $this->squareMil, 1), 0.0001);
        $this->assertEqualsWithDelta(6.4516E-10, $this->converter->convert($this->squareMil, $this->squareMeter, 1), 0.001);
    }

    public function test_squareMeter_acre(): void
    {
        $this->assertEqualsWithDelta(0.0002471, $this->converter->convert($this->squareMeter, $this->acre, 1), 0.0001);
        $this->assertEqualsWithDelta(4046.86, $this->converter->convert($this->acre, $this->squareMeter, 1), 0.0001);
    }

    public function test_squareMeter_hectare(): void
    {
        $this->assertEqualsWithDelta(1e-8, $this->converter->convert($this->squareMeter, $this->hectare, 1), 0.0001);
        $this->assertEquals(10_000, $this->converter->convert($this->hectare, $this->squareMeter, 1));
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
}
