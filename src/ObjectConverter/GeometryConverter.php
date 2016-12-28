<?php

namespace Brick\ObjectConverter;

use Brick\Geo\Geometry;

use Brick\Geo\Exception\GeometryException;
use Brick\ObjectConverter\Exception\ObjectNotConvertibleException;

/**
 * Handles conversion between date-time objects and strings.
 */
class GeometryConverter implements ObjectConverter
{
    /**
     * The default SRID to assign to the geometry when reading WKT.
     *
     * @var int
     */
    private $srid;

    /**
     * @param int $srid The default SRID to assign to the geometry when reading WKT.
     */
    public function __construct($srid = 0)
    {
        $this->srid = $srid;
    }

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
                $geometry = Geometry::fromText($value, $this->srid);

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
