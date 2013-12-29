<?php

namespace Brick\FileSystem;

/**
 * Exception thrown when a file system operation fails.
 */
class FileSystemException extends \RuntimeException
{
    /**
     * @param \Exception $e
     *
     * @return FileSystemException
     */
    public static function wrap(\Exception $e)
    {
        return new static($e->getMessage(), $e->getCode(), $e);
    }

    /**
     * @param string $path
     *
     * @return FileSystemException
     */
    public static function cannotGetRealPath($path)
    {
        return new self(sprintf(
            'Cannot get the real path of "%s"; check that the path exists.',
            $path
        ));
    }
}
