<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Vesper\UnitConversion\Converter;
use Vesper\UnitConversion\Dimension;
use Vesper\UnitConversion\FactorUnitPart;
use Vesper\UnitConversion\RegistryBuilder;
use Vesper\UnitConversion\Unit;
use Vesper\UnitConversion\UnitPart;
use Vesper\UnitConversion\Registry;

final class PressureTest extends TestCase
{
    protected Registry $registry;
    protected Unit $pa;
    protected Unit $psi;
    protected Unit $atm;
    protected Unit $bar;
    protected Unit $torr;
    protected Unit $mmHg;
    protected Unit $mmH2O;
    protected Unit $cmH2O;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registry = new Registry();

        $this->registry->register(
            'pa',
            new Unit(
                new UnitPart(1, Dimension::MASS, 1),
                new UnitPart(1, Dimension::LENGTH, -1),
                new UnitPart(1, Dimension::TIME, -2)
            )
        );

        $this->pa = $this->registry->get('pa');

        $this->psi = new Unit(
            new FactorUnitPart(6894.75729276459),
            ...$this->pa->getParts()
        );

        $this->atm = new Unit(
            new FactorUnitPart(101325),
            ...$this->pa->getParts()
        );

        $this->bar = new Unit(
            new FactorUnitPart(100000),
            ...$this->pa->getParts()
        );

        $this->torr = new Unit(
            new FactorUnitPart(133.322),
            ...$this->pa->getParts()
        );

        $this->mmHg = new Unit(
            new FactorUnitPart(133.322),
            ...$this->pa->getParts()
        );

        $this->mmH2O = new Unit(
            new FactorUnitPart(9.80665),
            ...$this->pa->getParts()
        );

        $this->cmH2O = new Unit(
            new FactorUnitPart(98.0665),
            ...$this->pa->getParts()
        );

        $this->converter = new Converter();
    }

    public function test_pa_psi(): void
    {
        $this->assertEqualsWithDelta(0.0001450377, $this->converter->convert($this->pa, $this->psi, 1), 0.000001);
        $this->assertEquals(6894.75729276459, $this->converter->convert($this->psi, $this->pa, 1));
    }

    public function test_pa_atm(): void
    {
        $this->assertEqualsWithDelta(0.0000098692, $this->converter->convert($this->pa, $this->atm, 1), 0.0000000001);
        $this->assertEquals(101325, $this->converter->convert($this->atm, $this->pa, 1));
    }

    public function test_pa_bar(): void
    {
        $this->assertEqualsWithDelta(0.00001, $this->converter->convert($this->pa, $this->bar, 1), 0.00001);
        $this->assertEquals(100000, $this->converter->convert($this->bar, $this->pa, 1));
    }

    public function test_pa_torr(): void
    {
        $this->assertEqualsWithDelta(0.0075006168, $this->converter->convert($this->pa, $this->torr, 1), 0.0000001);
        $this->assertEquals(133.322, $this->converter->convert($this->torr, $this->pa, 1));
    }

    public function test_pa_mmHg(): void
    {
        $this->assertEqualsWithDelta(0.00750061683, $this->converter->convert($this->pa, $this->mmHg, 1), 0.000001);
        $this->assertEquals(133.322, $this->converter->convert($this->mmHg, $this->pa, 1));
    }

    public function test_pa_mmH2O(): void
    {
        $this->assertEqualsWithDelta(0.1019716212, $this->converter->convert($this->pa, $this->mmH2O, 1), 0.00001);
        $this->assertEquals(9.80665, $this->converter->convert($this->mmH2O, $this->pa, 1));
    }

    public function test_pa_cmH2O(): void
    {
        $this->assertEqualsWithDelta(0.01019716, $this->converter->convert($this->pa, $this->cmH2O, 1), 0.000001);
        $this->assertEquals(98.0665, $this->converter->convert($this->cmH2O, $this->pa, 1));
    }
}