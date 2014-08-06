<?php

namespace Brick\Doctrine\Types\DateTime;

use Brick\DateTime\LocalDateTime;
use Brick\Doctrine\Types\UnexpectedValueException;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Doctrine type for LocalDateTime.
 */
class LocalDateTimeType extends Type
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'LocalDateTime';
    }

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDateTimeTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof LocalDateTime) {
            return (string) $value;
        }

        throw new UnexpectedValueException(LocalDateTime::class, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return LocalDateTime::parse(str_replace(' ', 'T', $value));
    }
}
