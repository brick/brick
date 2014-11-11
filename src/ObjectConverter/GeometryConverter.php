<?php

namespace Brick\ObjectConverter;

use Brick\Geo\Geometry;

use Brick\Geo\GeometryException;
use Brick\ObjectConverter\Exception\ObjectNotConvertibleException;

/**
 * Handles conversion between date-time objects and strings.
 */
class GeometryConverter implements ObjectConverter
{
    /**
     * {@inheritdoc}
     */
    public function shrink($object)
    {
        if ($object instanceof Geometry) {
            return $object->asText();
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function expand($className, $value, array $options = [])
    {
        if ($className == Geometry::class || is_subclass_of($className, Geometry::class)) {
            try {
                $geometry = Geometry::fromText($value);

                if (! $geometry instanceof $className) {
                    throw new ObjectNotConvertibleException(sprintf(
                        'Expected instance of %s, got instance of %s.',
                        $className,
                        get_class($geometry)
                    ));
                }

                return $geometry;
            } catch (GeometryException $e) {
                throw new ObjectNotConvertibleException($e->getMessage(), $e->getCode(), $e);
            }
        }

        return null;
    }
}
