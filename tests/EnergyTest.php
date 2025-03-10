<?php declare(strict_types=1);

Use Vesper\Conversion\Converter;
Use Vesper\Conversion\Dimension;
Use Vesper\Conversion\Unit;
Use Vesper\Conversion\UnitPart;
use PHPUnit\Framework\TestCase;

final class EnergyTest extends TestCase
{
    protected Unit $cal;
    protected Unit $joule;
    protected Converter $converter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cal = new Unit(
            new UnitPart(4.184, Dimension::MASS, 1),
            new UnitPart(1, Dimension::LENGTH, 2),
            new UnitPart(1, Dimension::TIME, -2)
        );
        $this->joule = new Unit(
            new UnitPart(1, Dimension::MASS, 1),
            new UnitPart(1, Dimension::LENGTH, 2),
            new UnitPart(1, Dimension::TIME, -2),
        );

        $this->converter = new Converter();
    }

    public function test_cal_to_joule(): void
    {
        $this->assertEqualsWithDelta(4.184, $this->converter->convert($this->cal, $this->joule, 1), 0.0000001);
    }

    public function test_joule_to_cal(): void
    {
        $this->assertEqualsWithDelta(1 / 4.184, $this->converter->convert($this->joule, $this->cal, 1), 0.0000001);
    }
}
