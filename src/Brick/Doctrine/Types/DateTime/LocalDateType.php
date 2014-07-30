<?php

namespace Brick\Doctrine\Types\DateTime;

use Brick\DateTime\LocalDate;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Doctrine type for LocalDate.
 */
class LocalDateType extends Type
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'LocalDate';
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
