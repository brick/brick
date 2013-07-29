<?php

namespace Brick\Doctrine\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Brick\Math\Decimal;

/**
 * Doctrine type for \Brick\Math\Decimal
 */
class BrickDecimalType extends Type
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'brickdecimal';
    }

    /**
     * @param  array                                     $fieldDeclaration
     * @param  \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDecimalTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * @param  string|null                               $value
     * @param  \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     * @return \Brick\Math\Decimal|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return Decimal::fromString($value);
    }

    /**
     * @param  \Brick\Math\Decimal|null                 $value
     * @param  \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     * @return string|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return (string) $value;
    }
}
