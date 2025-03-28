<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Vesper\UnitConversion\Converter;
use Vesper\UnitConversion\Dimension;
use Vesper\UnitConversion\FactorUnitPart;
use Vesper\UnitConversion\RegistryBuilder;
use Vesper\UnitConversion\Unit;
use Vesper\UnitConversion\UnitPart;
use Vesper\UnitConversion\Registry;

final class ForceTest extends TestCase
{
    protected Registry $registry;
    protected Unit $newton;
    protected Unit $dyne;
    protected Unit $poundforce;
    protected Unit $kip;
    protected Unit $kilogramforce;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registry = new Registry();

        RegistryBuilder::registerSiUnit(
            $this->registry,
            'newton',
            ['n'],
            new Unit(
                new UnitPart(1, Dimension::MASS, 1),
                new UnitPart(1, Dimension::LENGTH, 1),
                new UnitPart(1, Dimension::TIME, -2)
            )
        );

        $this->newton = $this->registry->get('newton');

        $this->dyne = new Unit(
            new FactorUnitPart(0.00001),
            ...$this->newton->getParts()
        );

        $this->poundforce = new Unit(
            new FactorUnitPart(4.4482216152605),
            ...$this->newton->getParts()
        );

        $this->kip = new Unit(
            new FactorUnitPart(4448.2216),
            ...$this->newton->getParts()
        );

        $this->kilogramforce = new Unit(
            new FactorUnitPart(9.8066),
            ...$this->newton->getParts()
        );

        $this->converter = new Converter();
    }

    public function test_newton_dune(): void
    {
        $this->assertEqualsWithDelta(100_000, $this->converter->convert($this->newton, $this->dyne, 1), 0.1);
        $this->assertEquals(0.00001, $this->converter->convert($this->dyne, $this->newton, 1));
    }

    public function test_newton_poundforce(): void
    {
        $this->assertEqualsWithDelta(0.2248, $this->converter->convert($this->newton, $this->poundforce, 1), 0.1);
        $this->assertEquals(4.4482216152605, $this->converter->convert($this->poundforce, $this->newton, 1));
    }

    public function test_newton_kip(): void
    {
        $this->assertEqualsWithDelta(0.0002248, $this->converter->convert($this->newton, $this->kip, 1), 0.00000001);
        $this->assertEquals(4448.2216, $this->converter->convert($this->kip, $this->newton, 1));
    }

    public function test_newton_kilogramforce(): void
    {
        $this->assertEqualsWithDelta(0.101972, $this->converter->convert($this->newton, $this->kilogramforce, 1), 0.00001);
        $this->assertEquals(9.8066, $this->converter->convert($this->kilogramforce, $this->newton, 1));
    }
}
