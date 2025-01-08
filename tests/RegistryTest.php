<?php declare(strict_types=1);

use Conversion\Registry;
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

        $this->assertEquals(0.001, $g->getRatio());
        $this->assertEquals('gram', $g->getName());
        $this->assertEquals(Type::MASS, $g->getType());

        $this->assertEquals(1, $kg->getRatio());
        $this->assertEquals('kilogram', $kg->getName());
        $this->assertEquals(Type::MASS, $kg->getType());
    }

    public function test_aliases_are_equal()
    {
        $gram = $this->registry->get('gram');
        $gramAlias = $this->registry->get('g');

        $this->assertNotNull($gram);
        $this->assertNotNull($gramAlias);
        $this->assertTrue($gram === $gramAlias);
    }

    public function test_alias_unknown_throws() {
        $this->assertNull($this->registry->get('yeet'));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Cannot alias unknown unit [yeet]');

        $this->registry->alias('yeet', ['y']);
    }

    public function test_alias_overwrite_throws()
    {
        $this->assertNotNull($this->registry->get('g'));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Adding [g] for [gram] would overwrite [gram]');

        $this->registry->alias('gram', ['g']);
    }
}
