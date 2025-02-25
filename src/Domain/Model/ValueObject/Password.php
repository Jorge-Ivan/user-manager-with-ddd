<?php
namespace UserManager\Domain\Model\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use UserManager\Domain\Exception\WeakPasswordException;

#[ORM\Embeddable]
final class Password
{
    #[ORM\Column(type: "string", length: 255)]
    private string $hashedValue;

    private const MIN_LENGTH = 8;
    private const PATTERN = '/^(?=.*[A-Z])(?=.*[0-9])(?=.*[^a-zA-Z0-9]).+$/';

    private function __construct(string $hashedPassword)
    {
        $this->hashedValue = $hashedPassword;
    }

    public static function fromPlainPassword(string $plainPassword): self
    {
        self::validatePassword($plainPassword);
        $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);
        return new self($hashedPassword);
    }

    public static function fromHash(string $hashedPassword): self
    {
        return new self($hashedPassword);
    }

    public static function validatePassword(string $plainPassword): void
    {
        if (strlen($plainPassword) < self::MIN_LENGTH) {
            throw new WeakPasswordException(
                sprintf('Password must be at least %d characters long', self::MIN_LENGTH)
            );
        }

        if (!preg_match(self::PATTERN, $plainPassword)) {
            throw new WeakPasswordException(
                'Password must contain at least one uppercase letter, one number, and one special character'
            );
        }
    }

    public function value(): string
    {
        return $this->hashedValue;
    }

    public function verify(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->hashedValue);
    }

    public function __toString(): string
    {
        return $this->hashedValue;
    }
}
