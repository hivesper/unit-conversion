<?php declare(strict_types=1);

use Conversion\Dimension;
use Conversion\Unit;
use Conversion\UnitPart;
use PHPUnit\Framework\TestCase;

final class UnitTest extends TestCase
{
    public function test_stringifies()
    {
        $unit = new Unit(
            new UnitPart('gram', 1, Dimension::MASS, 1),
            new UnitPart('meter^2', 1, Dimension::LENGTH, 2),
        );

        $this->assertEquals('gram/meter^2', (string) $unit);
    }

    public function test_get_dimensions()
    {
        $unit = new Unit(
            new UnitPart('gram', 1, Dimension::MASS, 1),
            new UnitPart('meter^2', 1, Dimension::LENGTH, 2),
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
            new UnitPart('meter', 1, Dimension::LENGTH, 1),
            new UnitPart('meter^2', 1, Dimension::LENGTH, 2),
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
}
