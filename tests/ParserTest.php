<?php declare(strict_types=1);

use Conversion\Dimension;
use Conversion\Parser;
use Conversion\Registry;
use Conversion\RegistryBuilder;
use PHPUnit\Framework\TestCase;

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

        $this->assertCount(1, $result->getParts());

        [$km] = $result->getParts();

        $this->assertEquals(Dimension::LENGTH, $km->getDimension());
        $this->assertEquals(1000, $km->getRatio());
        $this->assertEquals(1, $km->getPower());
    }

    public function test_parses_compound_division()
    {
        $result = $this->parser->parse('kilometer/hour');

        $this->assertCount(2, $result->getParts());

        [$km, $hour] = $result->getParts();

        $this->assertEquals(Dimension::LENGTH, $km->getDimension());
        $this->assertEquals(1000, $km->getRatio());
        $this->assertEquals(1, $km->getPower());

        $this->assertEquals(Dimension::TIME, $hour->getDimension());
        $this->assertEquals(3600, $hour->getRatio());
        $this->assertEquals(-1, $hour->getPower());
    }

    public function test_parses_compound_multiplication()
    {
        $result = $this->parser->parse('kilometer*hour');

        $this->assertCount(2, $result->getParts());

        [$km, $hour] = $result->getParts();

        $this->assertEquals(Dimension::LENGTH, $km->getDimension());
        $this->assertEquals(1000, $km->getRatio());
        $this->assertEquals(1, $km->getPower());

        $this->assertEquals(Dimension::TIME, $hour->getDimension());
        $this->assertEquals(3600, $hour->getRatio());
        $this->assertEquals(1, $hour->getPower());
    }

    public function test_parses_powers()
    {
        $result = $this->parser->parse('kilometer^3');

        $this->assertCount(1, $result->getParts());

        [$km3] = $result->getParts();

        $this->assertEquals(Dimension::LENGTH, $km3->getDimension());
        $this->assertEquals(1000, $km3->getRatio());
        $this->assertEquals(3, $km3->getPower());
    }

    public function test_parses_negative_powers()
    {
        $result = $this->parser->parse('kilometer^-3');

        $this->assertCount(1, $result->getParts());

        [$km3] = $result->getParts();

        $this->assertEquals(Dimension::LENGTH, $km3->getDimension());
        $this->assertEquals(1000, $km3->getRatio());
        $this->assertEquals(-3, $km3->getPower());
    }

    public function test_parses_compound_division_negative_powers()
    {
        $result = $this->parser->parse('kilometer/hour^-2');

        $this->assertCount(2, $result->getParts());

        [$km, $hour] = $result->getParts();

        $this->assertEquals(Dimension::LENGTH, $km->getDimension());
        $this->assertEquals(1000, $km->getRatio());
        $this->assertEquals(1, $km->getPower());

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

        $this->assertCount(2, $result->getParts());

        [$km3, $hour] = $result->getParts();

        $this->assertEquals(Dimension::LENGTH, $km3->getDimension());
        $this->assertEquals(1000, $km3->getRatio());
        $this->assertEquals(3, $km3->getPower());

        $this->assertEquals(Dimension::TIME, $hour->getDimension());
        $this->assertEquals(3600, $hour->getRatio());
        $this->assertEquals(-1, $hour->getPower());
    }

    public function test_throws_on_unknown_token()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unknown unit: yeet');

        $this->parser->parse('kilometer / yeet');
    }
}
