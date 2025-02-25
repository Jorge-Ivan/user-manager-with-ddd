<?php
namespace Tests\Unit\Domain\Model\ValueObject;

use UserManager\Domain\Exception\WeakPasswordException;
use UserManager\Domain\Model\ValueObject\Password;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    public function testValidPassword(): void
    {
        $password = Password::fromPlainPassword('Pass123!');
        $this->assertTrue($password->verify('Pass123!'));
    }

    public function testPasswordTooShort(): void
    {
        $this->expectException(WeakPasswordException::class);
        Password::fromPlainPassword('Pass1!');
    }

    public function testPasswordWithoutUppercase(): void
    {
        $this->expectException(WeakPasswordException::class);
        Password::fromPlainPassword('password123!');
    }

    public function testPasswordWithoutNumber(): void
    {
        $this->expectException(WeakPasswordException::class);
        Password::fromPlainPassword('Password!');
    }

    public function testPasswordWithoutSpecialChar(): void
    {
        $this->expectException(WeakPasswordException::class);
        Password::fromPlainPassword('Password123');
    }

    public function testPasswordVerification(): void
    {
        $password = Password::fromPlainPassword('Password123!');
        $this->assertTrue($password->verify('Password123!'));
        $this->assertFalse($password->verify('WrongPassword123!'));
    }
}