<?php declare(strict_types=1);

use Conversion\Dimension;
use Conversion\Registry;
use Conversion\RegistryBuilder;
use PHPUnit\Framework\TestCase;

final class RegistryTest extends TestCase
{
    protected Registry $registry;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registry = RegistryBuilder::build(new Registry());
    }

    public function test_unknown_unit()
    {
        $unitPart = $this->registry->get('yeet');

        $this->assertNull($unitPart);
    }

    public function test_length_units()
    {
        [$m] = $this->registry->get('meter')->getParts();

        $this->assertEquals(1, $m->getRatio());
        $this->assertEquals(Dimension::LENGTH, $m->getDimension());
        $this->assertEquals(1, $m->getPower());
    }

    public function test_mass_units()
    {
        [$g]= $this->registry->get('gram')->getParts();
        [$kg] = $this->registry->get('kilogram')->getParts();

        $this->assertEquals(0.001, $g->getRatio());
        $this->assertEquals('kilogram', $g->getName());
        $this->assertEquals(Dimension::MASS, $g->getDimension());

        $this->assertEquals(1, $kg->getRatio());
        $this->assertEquals('kilogram', $kg->getName());
        $this->assertEquals(Dimension::MASS, $kg->getDimension());
    }

    public function test_aliases_are_equal()
    {
       [$gram] = $this->registry->get('gram')->getParts();
       [$gramAlias] = $this->registry->get('g')->getParts();

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
        $this->expectExceptionMessage('Name [g] is already registered');

        $this->registry->alias('gram', ['g']);
    }

    public function test_has()
    {
        $this->assertTrue($this->registry->has('gram'));
        $this->assertFalse($this->registry->has('yeet'));
    }
}
