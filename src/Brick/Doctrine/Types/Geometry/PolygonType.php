<?php

namespace Brick\Doctrine\Types\Geometry;

use Brick\Geo\Polygon;

/**
 * Doctrine type for \Brick\Geo\Polygon.
 */
class PolygonType extends GeometryType
{
    /**
     * @return string
     */
    public function getName()
    {
        return GeometryType::POLYGON;
    }

    /**
     * @param  string              $wkb
     * @return \Brick\Geo\Polygon
     */
    protected static function convertFromWkb($wkb)
    {
        return Polygon::fromBinary($wkb);
    }
}
