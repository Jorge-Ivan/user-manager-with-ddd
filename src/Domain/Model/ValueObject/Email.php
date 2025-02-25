<?php
namespace UserManager\Domain\Model\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use UserManager\Domain\Exception\InvalidEmailException;

#[ORM\Embeddable]
final class Email
{
    #[ORM\Column(type: "string", length: 255, unique: true)]
    private string $value;

    private function __construct(string $email)
    {
        $this->validate($email);
        $this->value = $email;
    }

    public static function fromString(string $email): self
    {
        return new self($email);
    }

    private function validate(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException('Invalid email format');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Email $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
