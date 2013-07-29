<?php

namespace Brick\Doctrine\Types\Geometry;

use Brick\Geo\MultiPolygon;

/**
 * Doctrine type for \Brick\Geo\MultiPolygon.
 */
class MultiPolygonType extends GeometryType
{
    /**
     * @return string
     */
    public function getName()
    {
        return GeometryType::MULTIPOLYGON;
    }

    /**
     * @param  string                   $wkb
     * @return \Brick\Geo\MultiPolygon
     */
    protected static function convertFromWkb($wkb)
    {
        return MultiPolygon::fromBinary($wkb);
    }
}
