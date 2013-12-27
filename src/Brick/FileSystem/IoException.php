<?php

namespace Brick\FileSystem;

/**
 * Exception thrown by the FileSystem class.
 */
class IoException extends \RuntimeException
{
    /**
     * @param \Exception $e
     *
     * @return IoException
     */
    public static function wrap(\Exception $e)
    {
        return new self($e->getMessage(), $e->getCode(), $e);
    }

    /**
     * @param string $path
     * @return IoException
     */
    public static function fileDoesNotExist($path)
    {
        return new self('File does not exist: ' . $path);
    }

    /**
     * @param string $path
     * @return IoException
     */
    public static function cannotRemoveDirectory($path)
    {
        return new self('Cannot remove directory: ' . $path);
    }

    /**
     * @param string $path
     * @return IoException
     */
    public static function cannotRemoveFile($path)
    {
        return new self('Cannot remove file: ' . $path);
    }
}
