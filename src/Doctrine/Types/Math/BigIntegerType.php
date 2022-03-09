<?php

declare(strict_types=1);

namespace Brick\Doctrine\Types\Math;

use Brick\Math\BigInteger;
use Brick\Doctrine\Types\UnexpectedValueException;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Doctrine type for BigInteger.
 */
class BigIntegerType extends Type
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'BigInteger';
    }

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getDecimalTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return BigInteger::of($value);
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof BigInteger) {
            return (string) $value;
        }

        throw new UnexpectedValueException(BigInteger::class, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
