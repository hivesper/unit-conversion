<?php declare(strict_types=1);

use Conversion\Registry;
use Conversion\SiPrefix;
use Conversion\Type;
use PHPUnit\Framework\TestCase;

final class RegistryTest extends TestCase
{
    protected Registry $registry;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registry = new Registry();
        $this->registry->init();
    }

    public function test_unknown_unit()
    {
        $unitPart = $this->registry->get('yeet');

        $this->assertNull($unitPart);
    }

    public function test_mass_units()
    {
        $g = $this->registry->get('gram');
        $kg = $this->registry->get('kilogram');

        $this->assertEquals(SiPrefix::DECA->value, $g->getRatio());
        $this->assertEquals('gram', $g->getName());
        $this->assertEquals(Type::MASS, $g->getType());

        $this->assertEquals(10 ** SiPrefix::KILO->value, $kg->getRatio());
        $this->assertEquals('kilogram', $kg->getName());
        $this->assertEquals(Type::MASS, $kg->getType());
    }
}
