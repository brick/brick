<?php

namespace Brick\Doctrine\Types\DateTime;

use Brick\DateTime\LocalDateTime;

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

        /** @var $value LocalDateTime */
        return str_replace('T', ' ', $value->toString());
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
