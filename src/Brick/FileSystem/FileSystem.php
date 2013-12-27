<?php

namespace Brick\FileSystem;

use Brick\ErrorHandler;

/**
 * Utility class for filesystem calls.
 */
class FileSystem
{
    /**
     * @var \Brick\ErrorHandler
     */
    private $errorHandler;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->errorHandler = new ErrorHandler(function (\ErrorException $e) {
            throw IoException::wrap($e);
        });
    }

    /**
     * Returns whether the given file or directory exists.
     *
     * @param string $path
     *
     * @return boolean
     *
     * @throws IoException If an unexpected error occurs.
     */
    public function exists($path)
    {
        return $this->errorHandler->swallow(E_WARNING, function() use ($path) {
            return file_exists($path);
        });
    }

    /**
     * Returns the canonicalized absolute path of the given file or directory.
     *
     * @param string $path The path.
     *
     * @return string The canonicalized path.
     *
     * @throws IoException If an error occurs.
     */
    public function realpath($path)
    {
        $path = @realpath($path);

        if ($path === false) {
            throw new IoException('Cannot get the real path of ' . $path);
        }

        return $path;
    }

    /**
     * Creates a directory.
     *
     * @param string  $path      The path of the directory to create.
     * @param integer $mode      The mode of the directory (ignored on Windows).
     * @param boolean $recursive Whether to recursively create nested directories.
     *
     * @return void
     *
     * @throws IoException If the directory already exists, or cannot be created.
     */
    public function createDirectory($path, $mode = 0777, $recursive = false)
    {
        if (! $this->tryCreateDirectory($path, $mode, $recursive)) {
            throw new IoException('Cannot create directory ' . $path);
        }
    }

    /**
     * @param string  $path
     * @param integer $mode
     * @param boolean $recursive
     *
     * @return boolean
     */
    public function tryCreateDirectory($path, $mode = 0777, $recursive = false)
    {
        return @ mkdir($path, $mode, $recursive);
    }

    /**
     * @param string $path          The file path.
     * @param string|resource $data The data to write to the file.
     * @param bool $append          Whether to append or overwrite an existing file.
     * @return int                  The number of bytes written.
     * @throws IoException          If an error occurs while writing.
     */
    public function put($path, $data, $append = false)
    {
        $flags = $append ? FILE_APPEND : 0;
        $result = @file_put_contents($path, $data, $flags);

        if ($result === false) {
            throw new IoException('Cannot write data to ' . $path);
        }

        return $result;
    }

    /**
     * @param string $path The file path.
     * @return string      The file contents.
     * @throws IoException If an error occurs while reading.
     */
    public function get($path)
    {
        $result = @file_get_contents($path);

        if ($result === false) {
            throw new IoException('Cannot read data from ' . $path);
        }

        return $result;
    }

    /**
     * Removes a file, link, or (recursively) a directory.
     *
     * @param string $path
     * @throws IoException
     */
    public function remove($path)
    {
        if (! file_exists($path)) {
            throw IoException::fileDoesNotExist($path);
        }

        if (is_dir($path)) {
            $files = new \FilesystemIterator($path, \FilesystemIterator::SKIP_DOTS);
            foreach ($files as $file) {
                $this->remove($file);
            }

            if (true !== @rmdir($path)) {
                throw IoException::cannotRemoveDirectory($path);
            }
        } else {
            if (true !== @unlink($path)) {
                throw IoException::cannotRemoveFile($path);
            }
        }
    }

    /**
     * Renames a file, or throws an exception.
     *
     * @param string $oldName
     * @param string $newName
     *
     * @return void
     *
     * @throws IoException
     */
    public function rename($oldName, $newName)
    {
        if (! $this->tryRename($oldName, $newName)) {
            throw new IoException('Cannot rename file');
        }
    }

    /**
     * Attempts to rename a file, and returns the success as a boolean.
     *
     * @param string $oldName The old file name.
     * @param string $newName The new file name.
     *
     * @return boolean Whether the rename was successful.
     */
    public function tryRename($oldName, $newName)
    {
        return @ rename($oldName, $newName);
    }

    /**
     * @param  string $source      The source file/folder.
     * @param  string $destination The destination path.
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    public function recursiveCopy($source, $destination)
    {
        if (file_exists($destination)) {
            throw new \RuntimeException('The destination path already exists');
        }

        if (is_file($source)) {
            copy($source, $destination);
        }
        elseif (is_dir($source)) {
            mkdir($destination);
            foreach (scandir($source) as $item) {
                if ($item != '.' && $item != '..') {
                    $this->recursiveCopy(
                        $source . DIRECTORY_SEPARATOR . $item,
                        $destination . DIRECTORY_SEPARATOR . $item
                    );
                }
            }
        } else {
            throw new \RuntimeException(sprintf('The source file "%s" is of an unsupported type', $source));
        }
    }

    /**
     * Recurses over a directory and sub-directories and executes a function on each path.
     *
     * @param string   $path     The directory path.
     * @param callable $callback The function to call, which will receive the following parameters:
     *                           - a SplFileInfo
     *                           - the relative path.
     *
     * @return void
     */
    public function foreachFile($path, callable $callback)
    {
        $this->browseDirectory($path, '', $callback);
    }

    /**
     * Internal function for foreachFile().
     *
     * @param string   $fullPath
     * @param string   $relativePath
     * @param callable $callback
     *
     * @return void
     */
    private function browseDirectory($fullPath, $relativePath, callable $callback)
    {
        $iterator = new \DirectoryIterator($fullPath);

        foreach ($iterator as $file) {
            /** @var $file \DirectoryIterator */
            if (! $file->isDot()) {
                $fileName = $file->getFilename();
                $fileFullPath = $fullPath . DIRECTORY_SEPARATOR . $fileName;
                $fileRelativePath = $relativePath;
                if ($fileRelativePath != '') {
                    $fileRelativePath .= DIRECTORY_SEPARATOR;
                }
                $fileRelativePath .= $file->getFilename();

                if ($file->isDir()) {
                    self::browseDirectory($fileFullPath, $fileRelativePath, $callback);
                } elseif ($file->isFile()) {
                    $callback($file, $relativePath);
                }
            }
        }
    }
}
