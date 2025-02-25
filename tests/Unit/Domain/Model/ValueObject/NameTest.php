<?php
namespace Tests\Unit\Domain\Model\ValueObject;

use UserManager\Domain\Model\ValueObject\Name;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    public function testValidName(): void
    {
        $name = Name::fromString('John Doe');
        $this->assertEquals('John Doe', $name->value());
    }

    public function testNameTooShort(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Name::fromString('J');
    }

    public function testNameWithInvalidCharacters(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Name::fromString('John123');
    }

    public function testNamesEquality(): void
    {
        $name1 = Name::fromString('John Doe');
        $name2 = Name::fromString('John Doe');
        $name3 = Name::fromString('Jane Doe');

        $this->assertTrue($name1->equals($name2));
        $this->assertFalse($name1->equals($name3));
    }

    public function testToString(): void
    {
        $name = Name::fromString('John Doe');
        $this->assertEquals('John Doe', (string)$name);
    }
}