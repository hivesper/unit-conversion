<?php declare(strict_types=1);

use Conversion\Dimension;
use Conversion\RatioBag;
use Conversion\Unit;
use Conversion\UnitPart;
use PHPUnit\Framework\TestCase;

final class RatioBagTest extends TestCase
{
    public function test_add()
    {
        $bag = new RatioBag();
        $from = new Unit(
            new UnitPart(1, Dimension::LENGTH, 1),
        );
        $to = new Unit(
            new UnitPart(1, Dimension::LENGTH, 2),
        );
        $convert = fn($value) => $value * 2;

        $bag->add($from, $to, $convert);

        $ratios = (fn() => $this->ratios)->call($bag);

        $this->assertCount(1, $ratios);
    }

    public function test_get()
    {
        $bag = new RatioBag();
        $from = new Unit(
            new UnitPart(1, Dimension::LENGTH, 1),
        );
        $to = new Unit(
            new UnitPart(1, Dimension::LENGTH, 2),
        );
        $convert = fn($value) => $value * 2;

        $bag->add($from, $to, $convert);

        $ratio = $bag->get($from, $to);

        $this->assertEquals($from, $ratio['from']);
        $this->assertEquals($to, $ratio['to']);
        $this->assertEquals($convert, $ratio['convert']);
    }
}
