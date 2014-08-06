<?php

namespace Brick\Doctrine\Types\DateTime;

use Brick\DateTime\Instant;
use Brick\Doctrine\Types\UnexpectedValueException;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Doctrine type for Instant.
 *
 * This type stores the epochSecond only in an integer column.
 * The nanoseconds are silently discarded.
 */
class InstantType extends Type
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Instant';
    }

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getIntegerTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof Instant) {
            return $value->getEpochSecond();
        }

        throw new UnexpectedValueException(Instant::class, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return Instant::of($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getBindingType()
    {
        return \PDO::PARAM_INT;
    }
}
