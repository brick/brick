<?php

namespace Brick\Doctrine\Types\DateTime;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

use Brick\DateTime\LocalDate;

/**
 * Doctrine type for \Brick\DateTime\LocalDate.
 */
class LocalDateType extends Type
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'localdate';
    }

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDateTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        /** @var $value LocalDate */
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

        return LocalDate::parse($value);
    }
}
