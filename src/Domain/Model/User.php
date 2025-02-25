<?php
namespace UserManager\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use UserManager\Domain\Event\UserRegisteredEvent;
use UserManager\Domain\Model\ValueObject\Email;
use UserManager\Domain\Model\ValueObject\Name;
use UserManager\Domain\Model\ValueObject\Password;
use UserManager\Domain\Model\ValueObject\UserId;

#[ORM\Entity]
#[ORM\Table(name: "users")]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: "app_user_id")]
    private UserId $id;

    #[ORM\Embedded(class: Name::class)]
    private Name $name;

    #[ORM\Embedded(class: Email::class)]
    private Email $email;

    #[ORM\Embedded(class: Password::class)]
    private Password $password;

    #[ORM\Column(type: "datetime_immutable", name: "created_at")]
    private \DateTimeImmutable $createdAt;

    private array $events = [];

    private function __construct(
        UserId $id,
        Name $name,
        Email $email,
        Password $password,
        \DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = $createdAt;
    }

    public static function register(
        UserId $id,
        Name $name,
        Email $email,
        Password $password
    ): self {
        $user = new self(
            $id,
            $name,
            $email,
            $password,
            new \DateTimeImmutable()
        );

        $user->recordEvent(new UserRegisteredEvent($user));

        return $user;
    }

    public static function reconstitute(
        UserId $id,
        Name $name,
        Email $email,
        Password $password,
        \DateTimeImmutable $createdAt
    ): self {
        return new self(
            $id,
            $name,
            $email,
            $password,
            $createdAt
        );
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): Password
    {
        return $this->password;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    private function recordEvent($event): void
    {
        $this->events[] = $event;
    }

    public function pullEvents(): array
    {
        $events = $this->events;
        $this->events = [];
        return $events;
    }
}
