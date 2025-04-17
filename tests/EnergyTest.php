<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Vesper\UnitConversion\Converter;
use Vesper\UnitConversion\Registry;
use Vesper\UnitConversion\RegistryBuilder;
use Vesper\UnitConversion\Unit;

final class EnergyTest extends TestCase
{
    protected Unit $cal;
    protected Unit $joule;
    protected Unit $watthour;
    protected Converter $converter;

    protected function setUp(): void
    {
        parent::setUp();

        $registry = new Registry();

        RegistryBuilder::build($registry);

        $this->cal = $registry->get('calorie');

        $this->joule = $registry->get('joule');

        $this->watthour = $registry->get('watt-hour');

        $this->converter = new Converter();

        // todo: remove
        $this->x = 123;
    }

    public function test_cal_to_joule(): void
    {
        $this->assertEqualsWithDelta(4.1868, $this->converter->convert($this->cal, $this->joule, 1), 0.0000001);
    }

    public function test_joule_to_cal(): void
    {
        $this->assertEqualsWithDelta(1, $this->converter->convert($this->joule, $this->cal, 4.1868), 0.0000001);
    }

    public function test_joule_to_watt_hour(): void
    {
        $this->assertEqualsWithDelta(1, $this->converter->convert($this->joule, $this->watthour, 3600), 0.0000001);
    }
}
