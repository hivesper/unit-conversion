<?php declare(strict_types=1);

use Conversion\Parser;
use Conversion\Registry;
use Conversion\RegistryBuilder;
use PHPUnit\Framework\TestCase;

final class ParserTest extends TestCase
{
    public Parser $parser;

    protected function setUp(): void
    {
        parent::setUp();

        $registry = RegistryBuilder::build(new Registry());
        $this->parser = new Parser($registry);
    }

    public function test_parses_compound_units()
    {
        $result = $this->parser->parse('kilometer/hour');

        $this->assertCount(2, $result->getParts());

        [$km, $hour] = $result->getParts();

        $this->assertEquals('kilometer', $km->getName());
        $this->assertEquals(1, $km->getPower());

        $this->assertEquals('hour', $hour->getName());
        $this->assertEquals(-1, $hour->getPower());
    }
}
