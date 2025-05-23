<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Vesper\UnitConversion\Dimension;
use Vesper\UnitConversion\Registry;
use Vesper\UnitConversion\Unit;
use Vesper\UnitConversion\UnitPart;
use Vesper\UnitConversion\Exceptions\InvalidUnitException;

final class RegistryTest extends TestCase
{
    protected Registry $registry;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registry = new Registry();

        $this->registry->register('gram', new Unit(new UnitPart(1, Dimension::MASS, 1)));
    }

    public function test_unknown_unit()
    {
        $unitPart = $this->registry->get('yeet');

        $this->assertNull($unitPart);
    }

    public function test_aliases_are_equal()
    {
        $this->registry->alias('gram', 'g');

        [$gram] = $this->registry->get('gram')->getParts();
        [$gramAlias] = $this->registry->get('g')->getParts();

        $this->assertNotNull($gram);
        $this->assertNotNull($gramAlias);
        $this->assertTrue($gram === $gramAlias);
    }

    public function test_alias_unknown_throws()
    {
        $this->assertNull($this->registry->get('yeet'));

        $this->expectException(InvalidUnitException::class);
        $this->expectExceptionMessage('Cannot alias unknown unit [yeet]');

        $this->registry->alias('yeet', 'y');
    }

    public function test_alias_overwrite_throws()
    {
        $this->expectException(InvalidUnitException::class);
        $this->expectExceptionMessage('Name [gram] is already registered');

        $this->registry->alias('gram', 'gram');
    }

    public function test_has()
    {
        $this->assertTrue($this->registry->has('gram'));
        $this->assertFalse($this->registry->has('yeet'));
    }
}
