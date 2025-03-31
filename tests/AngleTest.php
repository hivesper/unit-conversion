<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Vesper\UnitConversion\Converter;
use Vesper\UnitConversion\RegistryBuilder;
use Vesper\UnitConversion\Unit;
use Vesper\UnitConversion\Registry;

final class AngleTest extends TestCase
{
    protected Unit $radian;
    protected Unit $degree;
    protected Unit $gradian;
    protected Unit $cycle;
    protected Unit $arcsec;
    protected Unit $arcmin;
    protected Converter $converter;

    protected function setUp(): void
    {
        parent::setUp();

        $registry = new Registry();

        RegistryBuilder::build($registry);

        $this->radian = $registry->get('radian');

        $this->degree = $registry->get('degree');

        $this->gradian = $registry->get('gradian');

        $this->cycle = $registry->get('cycle');

        $this->arcsec = $registry->get('arcsec');

        $this->arcmin = $registry->get('arcmin');

        $this->converter = new Converter();
    }

    public function test_radian_degree(): void
    {
        $this->assertEqualsWithDelta(57.2957, $this->converter->convert($this->radian, $this->degree, 1), 0.0001);
        $this->assertEquals(M_PI/180, $this->converter->convert($this->degree, $this->radian, 1));
    }

    public function test_radian_gradian(): void
    {
        $this->assertEqualsWithDelta(63.662, $this->converter->convert($this->radian, $this->gradian, 1), 0.001);
        $this->assertEquals(M_PI/200, $this->converter->convert($this->gradian, $this->radian, 1));
    }

    public function test_radian_cycle(): void
    {
        $this->assertEqualsWithDelta(0.1592 , $this->converter->convert($this->radian, $this->cycle, 1), 0.001);
        $this->assertEquals(M_PI*2, $this->converter->convert($this->cycle, $this->radian, 1));
    }

    public function test_radian_arcsec(): void
    {
        $this->assertEqualsWithDelta(206_264.80, $this->converter->convert($this->radian, $this->arcsec, 1), 0.01);
        $this->assertEquals(M_PI/(180*3600), $this->converter->convert($this->arcsec, $this->radian, 1));
    }

    public function test_radian_arcmin(): void
    {
        $this->assertEqualsWithDelta(3437.74, $this->converter->convert($this->radian, $this->arcmin, 1), 0.1);
        $this->assertEquals(M_PI/(180*60), $this->converter->convert($this->arcmin, $this->radian, 1));
    }
}