<?php declare(strict_types=1);

Use Vesper\UnitConversion\Dimension;
Use Vesper\UnitConversion\FactorUnitPart;
Use Vesper\UnitConversion\Registry;
Use Vesper\UnitConversion\RegistryBuilder;
Use Vesper\UnitConversion\Unit;
Use Vesper\UnitConversion\UnitPart;
use PHPUnit\Framework\TestCase;

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
