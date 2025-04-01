<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Vesper\UnitConversion\Converter;
use Vesper\UnitConversion\RegistryBuilder;
use Vesper\UnitConversion\Unit;
use Vesper\UnitConversion\Registry;

final class PowerTest extends TestCase
{
    protected Unit $watt;
    protected Unit $hp;
    protected Converter $converter;

    protected function setUp(): void
    {
        parent::setUp();

        $registry = new Registry();

        RegistryBuilder::build($registry);

        $this->watt = $registry->get('watt');

        $this->hp = $registry->get('hp');

        $this->converter = new Converter();
    }

    public function test_watt_hp(): void
    {
        $this->assertEqualsWithDelta(0.001341, $this->converter->convert($this->watt, $this->hp, 1), 0.0001);
        $this->assertEquals(745.6998715386, $this->converter->convert($this->hp, $this->watt, 1));
    }
}