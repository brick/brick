<?php

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
    public function getName()
    {
        return 'BigInteger';
    }

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDecimalTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * {@inheritdoc}
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
}
