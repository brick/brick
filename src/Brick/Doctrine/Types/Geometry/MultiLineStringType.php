<?php

namespace Brick\Doctrine\Types\Geometry;

use Brick\Geo\MultiLineString;

/**
 * Doctrine type for \Brick\Geo\MultiLineString.
 */
class MultiLineStringType extends GeometryType
{
    /**
     * @return string
     */
    public function getName()
    {
        return GeometryType::MULTILINESTRING;
    }

    /**
     * @param  string                      $wkb
     * @return \Brick\Geo\MultiLineString
     */
    protected static function convertFromWkb($wkb)
    {
        return MultiLineString::fromBinary($wkb);
    }
}
