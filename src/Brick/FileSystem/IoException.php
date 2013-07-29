<?php

namespace Brick\FileSystem;

/**
 * Exception thrown by the FileSystem class.
 */
class IoException extends \RuntimeException
{
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
