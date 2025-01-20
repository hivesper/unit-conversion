<?php declare(strict_types=1);

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

    public function test_parses_compound_units()
    {
        $result = $this->parser->parse('kilometer/hour');

        $this->assertCount(2, $result->getParts());

        [$km, $hour] = $result->getParts();

        $this->assertEquals($this->registry->get('kilometer')[0], $km);
        $this->assertEquals($this->registry->get('hour')[0], $hour);
    }
}
