<?php declare(strict_types=1);

Use Vesper\Conversion\Converter;
Use Vesper\Conversion\Dimension;
Use Vesper\Conversion\Unit;
Use Vesper\Conversion\UnitPart;
use PHPUnit\Framework\TestCase;

final class MassTest extends TestCase
{
    protected Unit $gram;
    protected Unit $ton;
    protected Unit $tonne;
    protected Unit $grain;
    protected Unit $dram;
    protected Unit $ounce;
    protected Unit $poundmass;
    protected Unit $hundredweight;
    protected Unit $stick;
    protected Unit $stone;

    protected Converter $converter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->gram = new Unit(new UnitPart(0.001, Dimension::MASS, 1));
        $this->ton = new Unit(new UnitPart(907.18474, Dimension::MASS, 1));
        $this->tonne = new Unit(new UnitPart(1000, Dimension::MASS, 1));
        $this->grain = new Unit(new UnitPart(64.79891e-6, Dimension::MASS, 1));
        $this->dram = new Unit(new UnitPart(1.7718451953125e-3, Dimension::MASS, 1));
        $this->ounce = new Unit(new UnitPart(28.349523125e-3, Dimension::MASS, 1));
        $this->poundmass = new Unit(new UnitPart(453.59237e-3, Dimension::MASS, 1));
        $this->hundredweight = new Unit(new UnitPart(45.359237, Dimension::MASS, 1));
        $this->stick = new Unit(new UnitPart(115e-3, Dimension::MASS, 1));
        $this->stone = new Unit(new UnitPart(6.35029318, Dimension::MASS, 1));

        $this->converter = new Converter();
    }

    public function test_gram_to_ton(): void
    {
        $this->assertEqualsWithDelta(1.1023113109244E-6, $this->converter->convert($this->gram, $this->ton, 1), 0.0000001);
        $this->assertEqualsWithDelta(907184.74, $this->converter->convert($this->ton, $this->gram, 1), 0.0000001);
    }

    public function test_gram_to_tonne(): void
    {
        $this->assertEqualsWithDelta(1e-6, $this->converter->convert($this->gram, $this->tonne, 1), 0.0000001);
        $this->assertEquals(1_000_000, $this->converter->convert($this->tonne, $this->gram, 1));
    }

    public function test_gram_to_grain(): void
    {
        $this->assertEqualsWithDelta(15.4323584, $this->converter->convert($this->gram, $this->grain, 1), 0.0000001);
        $this->assertEqualsWithDelta(0.06479891, $this->converter->convert($this->grain, $this->gram, 1), 0.00000001);
    }

    public function test_gram_to_dram(): void
    {
        $this->assertEqualsWithDelta(0.56438339119329, $this->converter->convert($this->gram, $this->dram, 1), 0.0000000001);
        $this->assertEquals(1.7718451953125, $this->converter->convert($this->dram, $this->gram, 1));
    }

    public function test_gram_to_ounce(): void
    {
        $this->assertEqualsWithDelta(0.03527396, $this->converter->convert($this->gram, $this->ounce, 1), 0.0000001);
        $this->assertEquals(28.349523125, $this->converter->convert($this->ounce, $this->gram, 1));
    }

    public function test_gram_to_poundmass(): void
    {
        $this->assertEqualsWithDelta(0.00220462, $this->converter->convert($this->gram, $this->poundmass, 1), 0.0000001);
        $this->assertEquals(453.59237, $this->converter->convert($this->poundmass, $this->gram, 1));
    }

    public function test_gram_to_hundredweight(): void
    {
        $this->assertEqualsWithDelta(0.0000220462, $this->converter->convert($this->gram, $this->hundredweight, 1), 0.0000000001);
        $this->assertEqualsWithDelta(45_359.237, $this->converter->convert($this->hundredweight, $this->gram, 1), 0.001);
    }

    public function test_gram_to_stick(): void
    {
        $this->assertEqualsWithDelta(0.00869565, $this->converter->convert($this->gram, $this->stick, 1), 0.0000001);
        $this->assertEquals(115, $this->converter->convert($this->stick, $this->gram, 1));
    }

    public function test_gram_to_stone(): void
    {
        $this->assertEqualsWithDelta(0.000157473, $this->converter->convert($this->gram, $this->stone, 1), 0.0000001);
        $this->assertEqualsWithDelta(6350.29318, $this->converter->convert($this->stone, $this->gram, 1), 0.00001);
    }
}
