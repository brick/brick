<?php

namespace Brick\Doctrine\Types\DateTime;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

use Brick\DateTime\LocalTime;

/**
 * Doctrine type for \Brick\DateTime\LocalTime.
 */
class LocalTimeType extends Type
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'localtime';
    }

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getTimeTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        /** @var $value LocalTime */
        return $value->toString();
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return LocalTime::parse($value);
    }
}
