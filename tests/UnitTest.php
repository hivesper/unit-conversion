<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Vesper\UnitConversion\Dimension;
use Vesper\UnitConversion\FactorUnitPart;
use Vesper\UnitConversion\Unit;
use Vesper\UnitConversion\UnitPart;

final class UnitTest extends TestCase
{
    public function test_stringifies()
    {
        $unit = new Unit(
            new UnitPart(1, Dimension::MASS, 1),
            new UnitPart(1, Dimension::LENGTH, -2),
        );

        $this->assertEquals('kilogram*meter^-2', (string)$unit);
    }

    public function test_stringifies_simplifies()
    {
        $unit = new Unit(
            new UnitPart(1, Dimension::LENGTH, 1),
            new UnitPart(1, Dimension::LENGTH, 1),
        );

        $this->assertEquals('meter^2', (string)$unit);
    }

    public function test_stringifies_with_factor()
    {
        $unit = new Unit(
            new FactorUnitPart(100),
            new UnitPart(1, Dimension::LENGTH, 2),
        );

        $this->assertEquals('meter^2', (string)$unit);
    }

    public function test_get_ratio(): void
    {
        $unit = new Unit(
            new UnitPart(2, Dimension::LENGTH, 2),
            new UnitPart(5, Dimension::LENGTH, 3),
        );

        $this->assertEquals(2 ** 2 * 5 ** 3, $unit->getRatio());
    }

    public function test_get_dimensions()
    {
        $unit = new Unit(
            new UnitPart(1, Dimension::MASS, 1),
            new UnitPart(1, Dimension::LENGTH, 2),
        );

        $this->assertEquals([
            Dimension::MASS->name => 1,
            Dimension::LENGTH->name => 2,
            Dimension::TIME->name => 0,
            Dimension::CURRENT->name => 0,
            Dimension::TEMPERATURE->name => 0,
            Dimension::LUMINOUS_INTENSITY->name => 0,
            Dimension::AMOUNT_OF_SUBSTANCE->name => 0,
            Dimension::ANGLE->name => 0,
        ], $unit->getDimensions());
    }

    public function test_get_dimensions_flatten()
    {
        $unit = new Unit(
            new UnitPart(1, Dimension::LENGTH, 1),
            new UnitPart(1, Dimension::LENGTH, 2),
        );

        $this->assertEquals([
            Dimension::MASS->name => 0,
            Dimension::LENGTH->name => 3,
            Dimension::TIME->name => 0,
            Dimension::CURRENT->name => 0,
            Dimension::TEMPERATURE->name => 0,
            Dimension::LUMINOUS_INTENSITY->name => 0,
            Dimension::AMOUNT_OF_SUBSTANCE->name => 0,
            Dimension::ANGLE->name => 0,
        ], $unit->getDimensions());
    }

    public function test_is_compound()
    {
        $this->assertFalse((new Unit(
            new UnitPart(1, Dimension::MASS, 1),
        ))->isCompound());

        // mass^1 * mass^2 = mass^3
        $this->assertFalse((new Unit(
            new UnitPart(1, Dimension::MASS, 1),
            new UnitPart(1, Dimension::MASS, 2),
        ))->isCompound());

        $this->assertTrue((new Unit(
            new UnitPart(1, Dimension::MASS, 1),
            new UnitPart(1, Dimension::LENGTH, 2),
        ))->isCompound());

        $this->assertTrue((new Unit(
            new UnitPart(1, Dimension::MASS, 1),
            new UnitPart(1, Dimension::LENGTH, -2),
        ))->isCompound());
    }

    public function test_can_convert_to()
    {
        $m = new Unit(
            new UnitPart(1, Dimension::LENGTH, 1),
        );
        $km = new Unit(
            new UnitPart(1000, Dimension::LENGTH, 1),
        );
        $km3 = new Unit(
            new UnitPart(1000, Dimension::LENGTH, 3),
        );
        $kg = new Unit(
            new UnitPart(1, Dimension::MASS, 1),
        );

        $this->assertTrue($m->canConvertTo($km));
        $this->assertTrue($km->canConvertTo($m));

        $this->assertFalse($m->canConvertTo($km3));
        $this->assertFalse($km3->canConvertTo($m));

        $this->assertFalse($m->canConvertTo($kg));
        $this->assertFalse($kg->canConvertTo($m));
    }

    public function test_multiply()
    {
        $m = new Unit(
            new UnitPart(1, Dimension::LENGTH, 1),
        );
        $s = new Unit(
            new UnitPart(1, Dimension::TIME, 1),
        );

        $ms = new Unit(
            new UnitPart(1, Dimension::LENGTH, 1),
            new UnitPart(1, Dimension::TIME, 1),
        );

        $meterMultipleSeconds = $m->multiply($s);

        $this->assertEquals($ms->getDimensions(), $meterMultipleSeconds->getDimensions());
    }

    public function test_divide()
    {
        $m = new Unit(
            new UnitPart(1, Dimension::LENGTH, 1),
        );
        $s = new Unit(
            new UnitPart(1, Dimension::TIME, 1),
        );

        $ms = new Unit(
            new UnitPart(1, Dimension::LENGTH, 1),
            new UnitPart(1, Dimension::TIME, -1),
        );

        $meterDividedBySeconds = $m->divide($s);

        $this->assertEquals($ms->getDimensions(), $meterDividedBySeconds->getDimensions());
    }

    public function test_invert()
    {
        $hundredSquareMeters = new Unit(
            new FactorUnitPart(100),
            new UnitPart(1, Dimension::LENGTH, 2),
        );

        $inverted = $hundredSquareMeters->invert();

        $this->assertEquals(-2, $inverted->getDimensions()[Dimension::LENGTH->name]);
        $this->assertEquals(0.01, $inverted->getRatio());
    }

    public function test_invert_twice()
    {
        $hundredSquareMeters = new Unit(
            new FactorUnitPart(100),
            new UnitPart(1, Dimension::LENGTH, 2),
        );

        $invertedTwice = $hundredSquareMeters->invert()->invert();

        $this->assertEquals($invertedTwice->getRatio(), $hundredSquareMeters->getRatio());
        $this->assertEquals($invertedTwice->getDimensions(), $hundredSquareMeters->getDimensions());
    }

    public function test_unit_and_factor_mix()
    {
        // 100m^2
        $hundredSquareMeters = new Unit(
            new FactorUnitPart(100),
            new UnitPart(1, Dimension::LENGTH, 2),
        );

        // (10m)^2
        $tenMetersSquared = new Unit(
            new UnitPart(10, Dimension::LENGTH, 2),
        );

        $this->assertEquals($hundredSquareMeters->getRatio(), $tenMetersSquared->getRatio());
        $this->assertEquals($hundredSquareMeters->getDimensions(), $tenMetersSquared->getDimensions());

        $this->assertEquals($hundredSquareMeters->invert()->getRatio(), $tenMetersSquared->invert()->getRatio());
        $this->assertEquals($hundredSquareMeters->invert()->getDimensions(), $tenMetersSquared->invert()->getDimensions());
    }
}
