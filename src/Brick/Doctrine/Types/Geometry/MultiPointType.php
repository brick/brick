<?php

namespace Brick\Doctrine\Types\Geometry;

use Brick\Geo\MultiPoint;

/**
 * Doctrine type for \Brick\Geo\MultiPoint.
 */
class MultiPointType extends GeometryType
{
    /**
     * @return string
     */
    public function getName()
    {
        return GeometryType::MULTIPOINT;
    }

    /**
     * @param  string                 $wkb
     * @return \Brick\Geo\MultiPoint
     */
    protected static function convertFromWkb($wkb)
    {
        return MultiPoint::fromBinary($wkb);
    }
}
