<?php
namespace UserManager\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use UserManager\Domain\Model\ValueObject\UserId;

final class UserIdType extends GuidType
{
    public const NAME = 'app_user_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof UserId) {
            return $value->value();
        }
        return $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value !== null ? UserId::fromString($value) : null;
    }

    public function getName()
    {
        return self::NAME;
    }
}
