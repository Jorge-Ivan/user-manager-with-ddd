<?php
namespace UserManager\Domain\Event;

use UserManager\Domain\Model\User;

final class UserRegisteredEvent implements DomainEvent
{
    private \DateTimeImmutable $occurredOn;
    private string $userId;
    private string $email;
    private string $name;

    public function __construct(User $user)
    {
        $this->occurredOn = new \DateTimeImmutable();
        $this->userId = $user->id()->value();
        $this->email = $user->email()->value();
        $this->name = $user->name()->value();
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function name(): string
    {
        return $this->name;
    }
}