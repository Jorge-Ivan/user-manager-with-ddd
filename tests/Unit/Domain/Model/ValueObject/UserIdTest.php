<?php
namespace Tests\Unit\Domain\Model\ValueObject;

use UserManager\Domain\Model\ValueObject\UserId;
use PHPUnit\Framework\TestCase;

class UserIdTest extends TestCase
{
    public function testGenerate(): void
    {
        $userId = UserId::generate();
        $this->assertNotEmpty($userId->value());
    }

    public function testFromValidString(): void
    {
        $uuid = 'f47ac10b-58cc-4372-a567-0e02b2c3d479';
        $userId = UserId::fromString($uuid);
        $this->assertEquals($uuid, $userId->value());
    }

    public function testFromInvalidString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        UserId::fromString('invalid-uuid');
    }

    public function testUserIdsEquality(): void
    {
        $uuid = 'f47ac10b-58cc-4372-a567-0e02b2c3d479';
        $userId1 = UserId::fromString($uuid);
        $userId2 = UserId::fromString($uuid);
        $userId3 = UserId::generate();

        $this->assertTrue($userId1->equals($userId2));
        $this->assertFalse($userId1->equals($userId3));
    }

    public function testToString(): void
    {
        $uuid = 'f47ac10b-58cc-4372-a567-0e02b2c3d479';
        $userId = UserId::fromString($uuid);
        $this->assertEquals($uuid, (string)$userId);
    }
}