<?php

namespace Brick\Doctrine\Types\Geometry;

use Brick\Geo\LineString;

/**
 * Doctrine type for \Brick\Geo\LineString.
 */
class LineStringType extends GeometryType
{
    /**
     * @return string
     */
    public function getName()
    {
        return GeometryType::LINESTRING;
    }

    /**
     * @param  string                 $wkb
     * @return \Brick\Geo\LineString
     */
    protected static function convertFromWkb($wkb)
    {
        return LineString::fromBinary($wkb);
    }
}
