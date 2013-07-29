<?php

namespace Brick\FileStorage;

/**
 * Exception thrown by classes implementing the Storage interface.
 */
class StorageException extends \RuntimeException
{
    /**
     * @param string $path
     * @param \Exception $previous
     * @return \Brick\FileStorage\StorageException
     */
    public static function putError($path, \Exception $previous = null)
    {
        return new self(sprintf('Could not put the given object at "%s".', $path), 0, $previous);
    }

    /**
     * @param string $path
     * @param \Exception $previous
     * @return \Brick\FileStorage\StorageException
     */
    public static function getError($path, \Exception $previous = null)
    {
        return new self(sprintf('Could not get the given object at "%s".', $path), 0, $previous);
    }

    /**
     * @param string $path
     * @param \Exception $previous
     * @return \Brick\FileStorage\StorageException
     */
    public static function deleteError($path, \Exception $previous = null)
    {
        return new self(sprintf('Could not remove the given object at "%s".', $path), 0, $previous);
    }
}
