<?php
namespace UserManager\Domain\Model\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class Name
{
    #[ORM\Column(type: "string", length: 100)]
    private string $value;

    private const MIN_LENGTH = 2;
    private const PATTERN = '/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/u';

    private function __construct(string $name)
    {
        $this->validate($name);
        $this->value = $name;
    }

    public static function fromString(string $name): self
    {
        return new self($name);
    }

    private function validate(string $name): void
    {
        if (strlen($name) < self::MIN_LENGTH) {
            throw new \InvalidArgumentException(
                sprintf('Name must be at least %d characters long', self::MIN_LENGTH)
            );
        }

        if (!preg_match(self::PATTERN, $name)) {
            throw new \InvalidArgumentException('Name contains invalid characters');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Name $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
