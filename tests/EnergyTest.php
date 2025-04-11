<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Vesper\UnitConversion\Converter;
use Vesper\UnitConversion\Registry;
use Vesper\UnitConversion\RegistryBuilder;
use Vesper\UnitConversion\Unit;
use Vesper\UnitConversion\UnitPart;

final class EnergyTest extends TestCase
{
    protected Unit $cal;
    protected Unit $joule;
    protected Converter $converter;

    protected function setUp(): void
    {
        parent::setUp();

        $registry = new Registry();

        RegistryBuilder::build($registry);

        $this->cal = $registry->get('calorie');

        $this->joule = $registry->get('joule');

        $this->converter = new Converter();
    }

    public function test_cal_to_joule(): void
    {
        $this->assertEqualsWithDelta(4.1868, $this->converter->convert($this->cal, $this->joule, 1), 0.0000001);
    }

    public function test_joule_to_cal(): void
    {
        $this->assertEqualsWithDelta(1 / 4.184, $this->converter->convert($this->joule, $this->cal, 1), 0.0000001);
    }
}
