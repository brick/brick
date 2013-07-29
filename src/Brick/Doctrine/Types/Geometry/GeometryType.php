<?php

namespace Brick\Doctrine\Types\Geometry;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Brick\Geo\Geometry;

/**
 * Doctrine type for \Brick\Geo\Geometry
 */
class GeometryType extends Type
{
    const GEOMETRY           = 'Geometry';
    const POINT              = 'Point';
    const LINESTRING         = 'LineString';
    const POLYGON            = 'Polygon';
    const MULTIPOINT         = 'MultiPoint';
    const MULTILINESTRING    = 'MultiLineString';
    const MULTIPOLYGON       = 'MultiPolygon';
    const GEOMETRYCOLLECTION = 'GeometryCollection';

    /**
     * Default SRID for Geometries;
     * This library assumes that all Geometries are in WGS84 Lon/Lat.
     *
     * @const integer
     */
    const WGS84 = 4326;

    /**
     * Child classes will override this method,
     * to ensure that the returned Geometry is of the expected type.
     *
     * @param  string   $wkb
     * @return Geometry
     */
    protected static function convertFromWkb($wkb)
    {
        return Geometry::fromBinary($wkb);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::GEOMETRY;
    }

    /**
     * @param  array                                     $fieldDeclaration
     * @param  \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return strtoupper($this->getName());
    }

    /**
     * @param  string|null                               $value
     * @param  \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     * @return \Brick\Geo\Geometry|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return static::convertFromWkb($value);
    }

    /**
     * @param  \Brick\Geo\Geometry|null                 $value
     * @param  \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     * @return string|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return $value->asBinary();
    }

    /**
     * @return boolean
     */
    public function canRequireSQLConversion()
    {
        return true;
    }

    /**
     * @param  string                                    $sqlExpr
     * @param  \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     * @return string
     */
    public function convertToDatabaseValueSQL($sqlExpr, AbstractPlatform $platform)
    {
        return sprintf('ST_GeomFromWkb(%s, %s)', $sqlExpr, self::WGS84);
    }

    /**
     * @param  string                                    $sqlExpr
     * @param  \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     * @return string
     */
    public function convertToPHPValueSQL($sqlExpr, $platform)
    {
        return sprintf('ST_AsWkb(%s)', $sqlExpr);
    }
}
