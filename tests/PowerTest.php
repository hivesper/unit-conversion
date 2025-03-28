<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Vesper\UnitConversion\Converter;
use Vesper\UnitConversion\Dimension;
use Vesper\UnitConversion\FactorUnitPart;
use Vesper\UnitConversion\RegistryBuilder;
use Vesper\UnitConversion\Unit;
use Vesper\UnitConversion\UnitPart;
use Vesper\UnitConversion\Registry;

final class PowerTest extends TestCase
{
    protected Registry $registry;
    protected Unit $watt;
    protected Unit $hp;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registry = new Registry();

        RegistryBuilder::registerSiUnit(
            $this->registry,
            'watt',
            ['w'],
            new Unit(
                new UnitPart(1, Dimension::MASS, 1),
                new UnitPart(1, Dimension::LENGTH, 2),
                new UnitPart(1, Dimension::TIME, -3)
            )
        );

        $this->watt = $this->registry->get('watt');

        $this->hp = new Unit(
            new FactorUnitPart(745.6998715386),
            ...$this->watt->getParts()
        );

        $this->converter = new Converter();
    }

    public function test_watt_hp(): void
    {
        $this->assertEqualsWithDelta(0.001341, $this->converter->convert($this->watt, $this->hp, 1), 0.0001);
        $this->assertEquals(745.6998715386, $this->converter->convert($this->hp, $this->watt, 1));
    }
}