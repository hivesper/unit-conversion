<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class UnitPartTest extends TestCase
{
    public function test_stringifies()
    {
        $unitPart = new UnitPart('meter', Type::LENGTH, 1);

        $this->assertEquals('meter', (string) $unitPart);
    }
}
