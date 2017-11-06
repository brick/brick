<?php

namespace Brick\FileStorage\Exception;

/**
 * Exception thrown by classes implementing the Storage interface.
 */
class StorageException extends \RuntimeException
{
    /**
     * @param string          $path
     * @param \Exception|null $previous
     *
     * @return StorageException
     */
    public static function putError(string $path, \Exception $previous = null) : StorageException
    {
        return new self(sprintf('Could not put the given object at "%s".', $path), 0, $previous);
    }

    /**
     * @param string          $path
     * @param \Exception|null $previous
     *
     * @return StorageException
     */
    public static function getError(string $path, \Exception $previous = null) : StorageException
    {
        return new self(sprintf('Could not get the given object at "%s".', $path), 0, $previous);
    }

    /**
     * @param string          $path
     * @param \Exception|null $previous
     *
     * @return StorageException
     */
    public static function deleteError(string $path, \Exception $previous = null) : StorageException
    {
        return new self(sprintf('Could not remove the given object at "%s".', $path), 0, $previous);
    }
}
