<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Vesper\UnitConversion\Dimension;
use Vesper\UnitConversion\FactorUnitPart;
use Vesper\UnitConversion\Registry;
use Vesper\UnitConversion\RegistryBuilder;
use Vesper\UnitConversion\Unit;
use Vesper\UnitConversion\UnitPart;

final class RegistryBuilderTest extends TestCase
{

    protected Registry $registry;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registry = new Registry();

        RegistryBuilder::registerSiUnit(
            $this->registry,
            'gram',
            ['g', 'gr'],
            new Unit(new UnitPart(1, Dimension::MASS, 1))
        );
    }

    public function test_register_si_unit_base(): void
    {
        $this->assertTrue($this->registry->has('gram'));
    }

    public function test_register_si_unit_base_aliases(): void
    {
        $gram = $this->registry->get('gram');
        $g = $this->registry->get('g');
        $gr = $this->registry->get('gr');

        $this->assertNotNull($gram);
        $this->assertTrue($gram === $g);
        $this->assertTrue($gram === $gr);
    }

    public function test_register_si_unit_prefixes_base(): void
    {
        foreach (RegistryBuilder::$siPrefixes as $prefix) {
            $name = $prefix['name'] . 'gram';

            $this->assertTrue($this->registry->has($name));

            [$factor, $g] = $this->registry->get($name)->getParts();

            $this->assertInstanceOf(FactorUnitPart::class, $factor);
            $this->assertEquals(10 ** $prefix['factor'], $factor->getRatio());

            $this->assertInstanceOf(UnitPart::class, $g);
            $this->assertEquals(1, $g->getRatio());
        }
    }

    public function test_register_si_unit_prefixes_alias(): void
    {
        foreach (RegistryBuilder::$siPrefixes as $prefix) {
            $gram = $this->registry->get($prefix['name'] . 'gram');
            $g = $this->registry->get($prefix['short_name'] . 'g');
            $gr = $this->registry->get($prefix['short_name'] . 'gr');

            $this->assertNotNull($gram);
            $this->assertTrue($gram === $g);
            $this->assertTrue($gram === $gr);
        }
    }
}
