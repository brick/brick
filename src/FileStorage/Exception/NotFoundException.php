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
     * @return \Brick\FileStorage\Exception\NotFoundException
     */
    public static function pathNotFound($path, \Exception $previous = null)
     {
         return new self(sprintf('The path "%s" does not exist', $path), 0, $previous);
     }
}
