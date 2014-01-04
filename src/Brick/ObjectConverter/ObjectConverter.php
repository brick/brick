<?php

namespace Brick\ObjectConverter;

use Brick\ObjectConverter\Exception\ObjectNotConvertibleException;
use Brick\ObjectConverter\Exception\ObjectNotFoundException;

/**
 * Shrinks and expands objects.
 */
interface ObjectConverter
{
    /**
     * Returns a flattened representation of the given object.
     *
     * The object must be reconstructible using expand() on the result of this method.
     *
     * @param object $object The object to flatten.
     *
     * @return string|array|null The representation, or null if the object is not targeted by this converter.
     *
     * @throws ObjectNotConvertibleException If the object is supported, but is not convertible for some reason.
     */
    public function shrink($object);

    /**
     * Reconstructs an object from its flattened representation.
     *
     * @param string       $className The class name of the object to reconstruct.
     * @param string|array $value     The flattened representation.
     * @param array        $options   An array of converter-specific options.
     *
     * @return object|null The object, or null if the class name is not targeted by this converter.
     *
     * @throws ObjectNotConvertibleException If the class name is supported, but the representation is not valid.
     * @throws ObjectNotFoundException       If the class name is supported, but the object cannot be found.
     */
    public function expand($className, $value, array $options = []);
}
