<?php
namespace Tests\Unit\Domain\Model;

use UserManager\Domain\Event\UserRegisteredEvent;
use UserManager\Domain\Model\User;
use UserManager\Domain\Model\ValueObject\Email;
use UserManager\Domain\Model\ValueObject\Name;
use UserManager\Domain\Model\ValueObject\Password;
use UserManager\Domain\Model\ValueObject\UserId;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testRegisterUser(): void
    {
        $userId = UserId::generate();
        $name = Name::fromString('John Doe');
        $email = Email::fromString('john.doe@example.com');
        $password = Password::fromPlainPassword('Password123!');

        $user = User::register($userId, $name, $email, $password);

        $this->assertTrue($userId->equals($user->id()));
        $this->assertTrue($name->equals($user->name()));
        $this->assertTrue($email->equals($user->email()));
        $this->assertTrue($password->value() === $user->password()->value());
        $this->assertInstanceOf(\DateTimeImmutable::class, $user->createdAt());

        $events = $user->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserRegisteredEvent::class, $events[0]);
    }

    public function testPullEvents(): void
    {
        $userId = UserId::generate();
        $name = Name::fromString('John Doe');
        $email = Email::fromString('john.doe@example.com');
        $password = Password::fromPlainPassword('Password123!');

        $user = User::register($userId, $name, $email, $password);

        $events = $user->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserRegisteredEvent::class, $events[0]);

        $eventsAfterPull = $user->pullEvents();
        $this->assertCount(0, $eventsAfterPull);
    }
}