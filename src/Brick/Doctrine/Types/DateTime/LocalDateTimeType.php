<?php

namespace Brick\Doctrine\Types\DateTime;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

use Brick\DateTime\LocalDateTime;

/**
 * Doctrine type for \Brick\DateTime\LocalDateTime.
 */
class LocalDateTimeType extends Type
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'localdatetime';
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
