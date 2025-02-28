<?php declare(strict_types=1);

use Conversion\Converter;
use Conversion\Dimension;
use Conversion\Unit;
use Conversion\UnitPart;
use PHPUnit\Framework\TestCase;

final class VolumeTest extends TestCase
{
    protected Unit $liter;
    protected Unit $m3;
    protected Converter $converter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->liter = new Unit(new UnitPart(0.1, Dimension::LENGTH, 3));
        $this->m3 = new Unit(new UnitPart(1, Dimension::LENGTH, 3));

        $this->converter = new Converter();
    }

    public function test_liter_to_m3(): void
    {
        $this->assertEqualsWithDelta(0.001, $this->converter->convert($this->liter, $this->m3, 1), 0.001);
    }

    public function test_m3_to_liter(): void
    {
        $this->assertEqualsWithDelta(1000, $this->converter->convert($this->m3, $this->liter, 1), 0.001);
    }

    public function test_volume_convert()
    {
        $km3 = new Unit(
            new UnitPart(1000, Dimension::LENGTH, 3),
        );
        $m3 = new Unit(
            new UnitPart(1, Dimension::LENGTH, 3),
        );
        $cm3 = new Unit(
            new UnitPart(0.01, Dimension::LENGTH, 3),
        );

        $this->assertEquals(2, $this->converter->convert($m3, $km3, 2_000_000_000));
        $this->assertEquals(2_000_000_000, $this->converter->convert($km3, $m3, 2));

        $this->assertEqualsWithDelta(2_000_000, $this->converter->convert($m3, $cm3, 2), 0.000001);
        $this->assertEqualsWithDelta(0.000002, $this->converter->convert($cm3, $m3, 2), 0.000001);
    }
}
