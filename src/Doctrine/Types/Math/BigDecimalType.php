<?php

declare(strict_types=1);

namespace Brick\Doctrine\Types\Math;

use Brick\Math\BigDecimal;
use Brick\Doctrine\Types\UnexpectedValueException;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Doctrine type for BigDecimal.
 */
class BigDecimalType extends Type
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'BigDecimal';
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

        return BigDecimal::of($value);
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

        if ($value instanceof BigDecimal) {
            return (string) $value;
        }

        throw new UnexpectedValueException(BigDecimal::class, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
