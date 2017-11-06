<?php

namespace Brick\FileStorage\Exception;

/**
 * Exception thrown when referencing a non existent path.
 */
class NotFoundException extends StorageException
{
    /**
     * @param string          $path
     * @param \Exception|null $previous
     *
     * @return NotFoundException
     */
    public static function pathNotFound(string $path, \Exception $previous = null) : NotFoundException
     {
         return new self(sprintf('The path "%s" does not exist', $path), 0, $previous);
     }
}
