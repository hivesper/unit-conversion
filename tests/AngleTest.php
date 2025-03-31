<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Vesper\UnitConversion\Converter;
use Vesper\UnitConversion\Dimension;
use Vesper\UnitConversion\FactorUnitPart;
use Vesper\UnitConversion\RegistryBuilder;
use Vesper\UnitConversion\Unit;
use Vesper\UnitConversion\UnitPart;
use Vesper\UnitConversion\Registry;

final class AngleTest extends TestCase
{
    protected Registry $registry;
    protected Unit $radian;
    protected Unit $degree;
    protected Unit $gradian;
    protected Unit $cycle;
    protected Unit $arcsec;
    protected Unit $arcmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registry = new Registry();

        RegistryBuilder::registerSiUnit(
            $this->registry,
            'radian',
            ['rad'],
            new Unit(
                new UnitPart(1, Dimension::ANGLE, 1),
            )
        );

        $this->radian = $this->registry->get('radian');

        $this->degree = new Unit(
            new FactorUnitPart(M_PI/180),
            ...$this->radian->getParts()
        );

        $this->gradian = new Unit(
            new FactorUnitPart(M_PI/200),
            ...$this->radian->getParts()
        );

        $this->cycle = new Unit(
            new FactorUnitPart(M_PI*2),
            ...$this->radian->getParts()
        );

        $this->arcsec = new Unit(
            new FactorUnitPart(M_PI/(180*3600)),
            ...$this->radian->getParts()
        );

        $this->arcmin = new Unit(
            new FactorUnitPart(M_PI/(180*60)),
            ...$this->radian->getParts()
        );

        $this->converter = new Converter();
    }

    public function test_radian_degree(): void
    {
        $this->assertEqualsWithDelta(  57.2957, $this->converter->convert($this->radian, $this->degree, 1), 0.0001);
        $this->assertEquals(M_PI/180, $this->converter->convert($this->degree, $this->radian, 1));
    }

    public function test_radian_gradian(): void
    {
        $this->assertEqualsWithDelta(63.662, $this->converter->convert($this->radian, $this->gradian, 1), 0.001);
        $this->assertEquals(M_PI/200, $this->converter->convert($this->gradian, $this->radian, 1));
    }

    public function test_radian_cycle(): void
    {
        $this->assertEqualsWithDelta( 0.1592 , $this->converter->convert($this->radian, $this->cycle, 1), 0.001);
        $this->assertEquals(M_PI*2, $this->converter->convert($this->cycle, $this->radian, 1));
    }

    public function test_radian_arcsec(): void
    {
        $this->assertEqualsWithDelta( 206_264.80, $this->converter->convert($this->radian, $this->arcsec, 1), 0.01);
        $this->assertEquals(M_PI/(180*3600), $this->converter->convert($this->arcsec, $this->radian, 1));
    }

    public function test_radian_arcmin(): void
    {
        $this->assertEqualsWithDelta(3437.74, $this->converter->convert($this->radian, $this->arcmin, 1), 0.1);
        $this->assertEquals(M_PI/(180*60), $this->converter->convert($this->arcmin, $this->radian, 1));
    }
}