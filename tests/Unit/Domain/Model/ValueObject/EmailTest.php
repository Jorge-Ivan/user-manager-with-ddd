<?php
namespace Tests\Unit\Domain\Model\ValueObject;

use UserManager\Domain\Exception\InvalidEmailException;
use UserManager\Domain\Model\ValueObject\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function testValidEmail(): void
    {
        $email = Email::fromString('test@example.com');
        $this->assertEquals('test@example.com', $email->value());
    }

    public function testInvalidEmail(): void
    {
        $this->expectException(InvalidEmailException::class);
        Email::fromString('invalid-email');
    }

    public function testEmailsEquality(): void
    {
        $email1 = Email::fromString('test@example.com');
        $email2 = Email::fromString('test@example.com');
        $email3 = Email::fromString('other@example.com');

        $this->assertTrue($email1->equals($email2));
        $this->assertFalse($email1->equals($email3));
    }

    public function testToString(): void
    {
        $email = Email::fromString('test@example.com');
        $this->assertEquals('test@example.com', (string)$email);
    }
}