<?php declare(strict_types=1);

use Conversion\Converter;
use Conversion\Dimension;
use Conversion\Unit;
use Conversion\UnitPart;
use PHPUnit\Framework\TestCase;

final class TimeTest extends TestCase
{
    protected Unit $second;
    protected Unit $minute;
    protected Unit $hour;
    protected Unit $day;

    protected Converter $converter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->second = new Unit(new UnitPart(1, Dimension::TIME, 1));
        $this->minute = new Unit(new UnitPart(60, Dimension::TIME, 1));
        $this->hour = new Unit(new UnitPart(3600, Dimension::TIME, 1));
        $this->day = new Unit(new UnitPart(86400, Dimension::TIME, 1));

        $this->converter = new Converter();
    }

    public function test_second_to_minute(): void
    {
        $this->assertEqualsWithDelta(0.0166667, $this->converter->convert($this->second, $this->minute, 1), 0.0000001);
        $this->assertEquals(60, $this->converter->convert($this->minute, $this->second, 1));
    }

    public function test_second_to_hour(): void
    {
        $this->assertEqualsWithDelta(0.000277778, $this->converter->convert($this->second, $this->hour, 1), 0.000000001);
        $this->assertEquals(3600, $this->converter->convert($this->hour, $this->second, 1));
    }

    public function test_second_to_day(): void
    {
        $this->assertEqualsWithDelta(0.0000115741, $this->converter->convert($this->second, $this->day, 1), 0.0000000001);
        $this->assertEquals(86400, $this->converter->convert($this->day, $this->second, 1));
    }
}
