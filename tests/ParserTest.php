<?php declare(strict_types=1);

use Conversion\Parser;
use Conversion\Registry;
use Conversion\Type;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ParserTest extends TestCase
{
    public Parser $parser;

    protected function setUp(): void
    {
        parent::setUp();

        $registry = new Registry();
        $registry->init();

        $this->parser = new Parser($registry);
    }

    #[DataProvider('provide_base_units')]
    public function test_parses_base_units(Type $type)
    {
        $result = $this->parser->parse($type->value);

        $this->assertCount(1, $result->getParts());
        $this->assertEquals($type, $result->getParts()[0]->getType());
    }

    public static function provide_base_units(): array
    {
        return array_map(fn ($type) => [
            $type,
        ], Type::cases());
    }

    public function test_parses_compound_units()
    {
        $result = $this->parser->parse('kilometer/hour');

        $this->assertCount(2, $result->getParts());
        $this->assertEquals('kilometer', (string) $result->getParts()[0]);
        $this->assertEquals('hour', (string) $result->getParts()[1]);
    }
}
