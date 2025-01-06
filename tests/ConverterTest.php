<?php declare(strict_types=1);

use Conversion\Converter;
use Conversion\Parser;
use Conversion\Registry;
use PHPUnit\Framework\TestCase;

final class ConverterTest extends TestCase
{
    protected Registry $registry;
    protected Parser $parser;
    protected Converter $converter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registry = new Registry();
        $this->registry->init();

        $this->parser = new Parser($this->registry);
        $this->converter = new Converter($this->registry);
    }

    public function test_converts_incompatible_throws()
    {
        $meter = $this->parser->parse('meter');
        $second = $this->parser->parse('second');

        $this->expectException(InvalidArgumentException::class);

        $this->converter->convert($meter, $second);
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
}
