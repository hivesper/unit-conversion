<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Vesper\UnitConversion\Dimension;
use Vesper\UnitConversion\Parser;
use Vesper\UnitConversion\Registry;
use Vesper\UnitConversion\RegistryBuilder;
use Vesper\UnitConversion\Exceptions\InvalidUnitException;
use Vesper\UnitConversion\Unit;
use Vesper\UnitConversion\UnitPart;

final class ParserTest extends TestCase
{
    protected Registry $registry;
    protected Parser $parser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registry = RegistryBuilder::build(new Registry());
        $this->parser = new Parser($this->registry);
    }

    public function test_parses_simple()
    {
        $result = $this->parser->parse('kilometer');

        $this->assertCount(2, $result->getParts());

        [$k, $m] = $result->getParts();

        $this->assertNull($k->getDimension());
        $this->assertEquals(1000, $k->getRatio());
        $this->assertEquals(1, $k->getPower());

        $this->assertEquals(Dimension::LENGTH, $m->getDimension());
        $this->assertEquals(1, $m->getRatio());
        $this->assertEquals(1, $m->getPower());
    }

    public function test_parses_compound_division()
    {
        $result = $this->parser->parse('kilometer/hour');

        $this->assertCount(3, $result->getParts());

        [$k, $m, $hour] = $result->getParts();

        $this->assertNull($k->getDimension());
        $this->assertEquals(1000, $k->getRatio());
        $this->assertEquals(1, $k->getPower());

        $this->assertEquals(Dimension::LENGTH, $m->getDimension());
        $this->assertEquals(1, $m->getRatio());
        $this->assertEquals(1, $m->getPower());

        $this->assertEquals(Dimension::TIME, $hour->getDimension());
        $this->assertEquals(3600, $hour->getRatio());
        $this->assertEquals(-1, $hour->getPower());
    }

    public function test_parses_compound_multiplication()
    {
        $result = $this->parser->parse('kilometer*hour');

        $this->assertCount(3, $result->getParts());

        [$k, $m, $hour] = $result->getParts();

        $this->assertNull($k->getDimension());
        $this->assertEquals(1000, $k->getRatio());
        $this->assertEquals(1, $k->getPower());

        $this->assertEquals(Dimension::LENGTH, $m->getDimension());
        $this->assertEquals(1, $m->getRatio());
        $this->assertEquals(1, $m->getPower());

        $this->assertEquals(Dimension::TIME, $hour->getDimension());
        $this->assertEquals(3600, $hour->getRatio());
        $this->assertEquals(1, $hour->getPower());
    }

    public function test_parses_powers()
    {
        $result = $this->parser->parse('kilometer^3');

        $this->assertCount(2, $result->getParts());

        [$k, $m3] = $result->getParts();

        $this->assertNull($k->getDimension());
        $this->assertEquals(1000, $k->getRatio());
        $this->assertEquals(1, $k->getPower());

        $this->assertEquals(Dimension::LENGTH, $m3->getDimension());
        $this->assertEquals(1, $m3->getRatio());
        $this->assertEquals(3, $m3->getPower());
    }

    public function test_parses_negative_powers()
    {
        $result = $this->parser->parse('kilometer^-3');

        $this->assertCount(2, $result->getParts());

        [$k, $m3] = $result->getParts();

        $this->assertNull($k->getDimension());
        $this->assertEquals(1000, $k->getRatio());
        $this->assertEquals(1, $k->getPower());

        $this->assertEquals(Dimension::LENGTH, $m3->getDimension());
        $this->assertEquals(1, $m3->getRatio());
        $this->assertEquals(-3, $m3->getPower());
    }

    public function test_parses_compound_division_negative_powers()
    {
        $result = $this->parser->parse('kilometer/hour^-2');

        $this->assertCount(3, $result->getParts());

        [$k, $m, $hour] = $result->getParts();

        $this->assertNull($k->getDimension());
        $this->assertEquals(1000, $k->getRatio());
        $this->assertEquals(1, $k->getPower());

        $this->assertEquals(Dimension::LENGTH, $m->getDimension());
        $this->assertEquals(1, $m->getRatio());
        $this->assertEquals(1, $m->getPower());

        $this->assertEquals(Dimension::TIME, $hour->getDimension());
        $this->assertEquals(3600, $hour->getRatio());
        $this->assertEquals(2, $hour->getPower());
    }

    public function test_parses_multiplies_powers()
    {
        $result = $this->parser->parse('liter^2');

        $this->assertCount(1, $result->getParts());

        [$l] = $result->getParts();

        $this->assertEquals(Dimension::LENGTH, $l->getDimension());
        $this->assertEquals(0.1, $l->getRatio());
        $this->assertEquals(6, $l->getPower());
    }

    public function test_ignores_whitespace()
    {
        $result = $this->parser->parse('   kilometer ^ 3   /   hour   ');

        $this->assertCount(3, $result->getParts());

        [$k, $m3, $hour] = $result->getParts();

        $this->assertNull($k->getDimension());
        $this->assertEquals(1000, $k->getRatio());
        $this->assertEquals(1, $k->getPower());

        $this->assertEquals(Dimension::LENGTH, $m3->getDimension());
        $this->assertEquals(1, $m3->getRatio());
        $this->assertEquals(3, $m3->getPower());

        $this->assertEquals(Dimension::TIME, $hour->getDimension());
        $this->assertEquals(3600, $hour->getRatio());
        $this->assertEquals(-1, $hour->getPower());
    }

    public function test_throws_on_unknown_token()
    {
        $this->expectException(InvalidUnitException::class);
        $this->expectExceptionMessage('Unknown unit: yeet');

        $this->parser->parse('kilometer / yeet');
    }

    public function test_parses_dash_separated_units()
    {
        $result = $this->parser->parse('gram-liter');
        [$gram, $liter] = $result->getParts();

        $this->assertCount(2, $result->getParts());

        $this->assertEquals(Dimension::MASS, $gram->getDimension());
        $this->assertEquals(0.001, $gram->getRatio());
        $this->assertEquals(1, $gram->getPower());

        $this->assertEquals(Dimension::LENGTH, $liter->getDimension());
        $this->assertEquals(0.1, $liter->getRatio());
        $this->assertEquals(3, $liter->getPower());
    }

    public function test_parses_dash_joined_unit_directly_if_registered()
    {
        $this->registry->register('watt-hour', new Unit(
        // Watt
            new UnitPart(1, Dimension::MASS, 1),
            new UnitPart(1, Dimension::LENGTH, 2),
            new UnitPart(1, Dimension::TIME, -3),
            // Hour
            new UnitPart(3600, Dimension::TIME, 1)
        ));

        $result = $this->parser->parse('watt-hour');
        [$wattMass, $wattLength, $wattTime, $hour] = $result->getParts();

        $this->assertCount(4, $result->getParts());

        // Watt
        $this->assertEquals(Dimension::MASS, $wattMass->getDimension());
        $this->assertEquals(1, $wattMass->getRatio());
        $this->assertEquals(1, $wattMass->getPower());

        $this->assertEquals(Dimension::LENGTH, $wattLength->getDimension());
        $this->assertEquals(1, $wattLength->getRatio());
        $this->assertEquals(2, $wattLength->getPower());

        $this->assertEquals(Dimension::TIME, $wattTime->getDimension());
        $this->assertEquals(1, $wattTime->getRatio());
        $this->assertEquals(-3, $wattTime->getPower());

        // Hour
        $this->assertEquals(Dimension::TIME, $hour->getDimension());
        $this->assertEquals(3600, $hour->getRatio());
        $this->assertEquals(1, $hour->getPower());
    }
}
