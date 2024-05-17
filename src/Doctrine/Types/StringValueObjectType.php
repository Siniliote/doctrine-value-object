<?php

declare(strict_types=1);

namespace Yokai\DoctrineValueObject\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use Webmozart\Assert\Assert;
use Yokai\DoctrineValueObject\StringValueObject;

final class StringValueObjectType extends Type
{
    use ValueObjectType;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        /** @var StringType $typeInherit */
        $typeInherit = $this->getType(Types::STRING);
        return $typeInherit->getSQLDeclaration($column, $platform);
    }

    /**
     * @inheritdoc
     */
    public static function getSupportedValueObjectType(): string
    {
        return StringValueObject::class;
    }

    /**
     * @inheritdoc
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if ($value === null) {
            return null;
        }

        Assert::isInstanceOf($value, $this->class);
        /** @var StringValueObject $value */

        /** @var StringType $typeInherit */
        $typeInherit = $this->getType(Types::STRING);
        return $typeInherit->convertToDatabaseValue($value->toValue(), $platform);
    }

    /**
     * @inheritdoc
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?StringValueObject
    {
        /** @var StringType $typeInherit */
        $typeInherit = $this->getType(Types::STRING);
        $value = $typeInherit->convertToPHPValue($value, $platform);

        if ($value === null) {
            return null;
        }

        Assert::string($value);

        /** @var StringValueObject $object */
        $object = \call_user_func([$this->class, 'fromValue'], $value);
        Assert::isInstanceOf($object, $this->class);

        return $object;
    }
}
