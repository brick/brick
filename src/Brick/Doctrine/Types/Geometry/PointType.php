<?php

namespace Brick\Doctrine\Types\Geometry;

use Brick\Geo\Point;

/**
 * Doctrine type for \Brick\Geo\Point.
 */
class PointType extends GeometryType
{
    /**
     * @return string
     */
    public function getName()
    {
        return GeometryType::POINT;
    }

    /**
     * @param  string            $wkb
     * @return \Brick\Geo\Point
     */
    protected static function convertFromWkb($wkb)
    {
        return Point::fromBinary($wkb);
    }
}
