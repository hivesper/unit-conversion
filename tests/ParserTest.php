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

    public function test_parses_dash_separated_unit_with_operator()
    {
        $result = $this->parser->parse('gram-liter/hour');
        [$gram, $liter, $hour] = $result->getParts();

        $this->assertCount(3, $result->getParts());

        $this->assertEquals(Dimension::MASS, $gram->getDimension());
        $this->assertEquals(0.001, $gram->getRatio());
        $this->assertEquals(1, $gram->getPower());

        $this->assertEquals(Dimension::LENGTH, $liter->getDimension());
        $this->assertEquals(0.1, $liter->getRatio());
        $this->assertEquals(3, $liter->getPower());

        $this->assertEquals(Dimension::TIME, $hour->getDimension());
        $this->assertEquals(3600, $hour->getRatio());
        $this->assertEquals(-1, $hour->getPower());
    }

    public function test_parses_dash_joined_single_unit_directly_if_registered()
    {
        $wattHour = $this->registry->get('watt-hour');
        $result = $this->parser->parse('watt-hour');
        [$wattMass, $wattLength, $wattTime, $hour] = $result->getParts();

        $this->assertCount(4, $result->getParts());
        $this->assertEquals($wattMass, $wattHour->getPart(0));
        $this->assertEquals($wattLength, $wattHour->getPart(1));
        $this->assertEquals($wattTime, $wattHour->getPart(2));
        $this->assertEquals($hour, $wattHour->getPart(3));
    }

    public function test_parses_dash_joined_single_unit_with_operator()
    {
        $wattHour = $this->registry->get('watt-hour');
        $result = $this->parser->parse('watt-hour/kg');
        [$wattMass, $wattLength, $wattTime, $hour, $kilo, $gram] = $result->getParts();

        $this->assertCount(6, $result->getParts());

        // Watt-hour
        $this->assertEquals($wattMass, $wattHour->getPart(0));
        $this->assertEquals($wattLength, $wattHour->getPart(1));
        $this->assertEquals($wattTime, $wattHour->getPart(2));
        $this->assertEquals($hour, $wattHour->getPart(3));

        // Kilogram
        $this->assertNull($kilo->getDimension());
        $this->assertEquals(1000, $kilo->getRatio());
        $this->assertEquals(1, $kilo->getPower());

        $this->assertEquals(Dimension::MASS, $gram->getDimension());
        $this->assertEquals(0.001, $gram->getRatio());
        $this->assertEquals(-1, $gram->getPower());
    }
}
