<?php

namespace Brick\PhoneNumber\Metadata;

/**
 * Base class for metadata classes.
 *
 * This class is internal to the PhoneNumber library, and is not part of the public API.
 */
abstract class AbstractMetadata
{
    /**
     * Recreates a metadata object exported with the var_export() function.
     *
     * @param array $data
     *
     * @return static
     */
    public static function __set_state(array $data)
    {
        $metadata = new static();

        foreach ($data as $key => $value) {
            $metadata->$key = $value;
        }

        return $metadata;
    }
}
