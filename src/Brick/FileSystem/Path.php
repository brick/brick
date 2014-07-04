<?php

namespace Brick\FileSystem;

use Brick\ErrorHandler;
use FilesystemIterator as FSI;

/**
 * Represents a path on the filesystem.
 */
class Path
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var \Brick\ErrorHandler|null
     */
    private static $errorHandler = null;

    /**
     * @var boolean
     */
    private static $throw = false;

    /**
     * Class constructor.
     *
     * @param string $path The filesystem path. It does not need to exist.
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Returns a new Path under the current one.
     *
     * @param string $path
     *
     * @return Path
     */
    public function under($path)
    {
        return new Path($this->path . DIRECTORY_SEPARATOR . $path);
    }

    /**
     * Returns whether this path exists.
     *
     * @return boolean True if the path exists, false otherwise.
     *
     * @throws FileSystemException If an error occurs.
     */
    public function exists()
    {
        return $this->swallow(E_WARNING, true, function() {
            return file_exists($this->path);
        });
    }

    /**
     * Returns the canonicalized absolute path of this path.
     *
     * @return string The canonicalized path.
     *
     * @throws FileSystemException If the path does not exist or an error occurs.
     */
    public function getRealPath()
    {
        $path = realpath($this->path);

        if ($path === false) {
            throw FileSystemException::cannotGetRealPath($path);
        }

        return $path;
    }

    /**
     * Returns whether this path points to a directory.
     *
     * @return boolean True if the path exists and is a directory, false otherwise.
     *
     * @throws FileSystemException If an error occurs.
     */
    public function isDirectory()
    {
        return $this->swallow(E_WARNING, true, function() {
            return is_dir($this->path);
        });
    }

    /**
     * Returns whether this path points to a file.
     *
     * @return boolean True if the path exists and is a file, false otherwise.
     *
     * @throws FileSystemException If an error occurs.
     */
    public function isFile()
    {
        return $this->swallow(E_WARNING, true, function() {
            return is_file($this->path);
        });
    }

    /**
     * Returns whether this path points to a symbolic link.
     *
     * @return boolean True if the path exists and is a symbolic link, false otherwise.
     *
     * @throws FileSystemException If an error occurs.
     */
    public function isSymbolicLink()
    {
        return $this->swallow(E_WARNING, true, function() {
            return is_link($this->path);
        });
    }

    /**
     * Creates a symbolic link to this path.
     *
     * @param string $path The link path.
     *
     * @return Path The current path.
     *
     * @throws FileSystemException If an error occurs.
     */
    public function createSymbolicLink($path)
    {
        $this->swallow(E_WARNING, true, function() use ($path) {
            symlink($this->path, $path);
        });

        return $this;
    }

    /**
     * Creates a hard link to this path.
     *
     * @param string $path The link path.
     *
     * @return Path The current path.
     *
     * @throws FileSystemException If an error occurs.
     */
    public function createLink($path)
    {
        $this->swallow(E_WARNING, true, function() use ($path) {
            link($this->path, $path);
        });

        return $this;
    }

    /**
     * Returns the target of the link this path points to.
     *
     * @return string The target of the link.
     *
     * @throws FileSystemException If the path does not exist, is not a link, or an error occurs.
     */
    public function readSymbolicLink()
    {
        return $this->swallow(E_WARNING, true, function() {
            return readlink($this->path);
        });
    }

    /**
     * Creates a directory in this path.
     *
     * @param integer $mode      The mode of the directory (ignored on Windows).
     * @param boolean $recursive Whether to create parent directories if they do not exist.
     *
     * @return Path The current path.
     *
     * @throws FileSystemException If the directory already exists or cannot be created.
     */
    public function createDirectory($mode = 0777, $recursive = false)
    {
        $this->swallow(E_WARNING, true, function() use ($mode, $recursive) {
            mkdir($this->path, $mode, $recursive);
        });

        return $this;
    }

    /**
     * Attempts to create a new directory in this path.
     *
     * Any error will be silently ignored and will make this method return false.
     * There is no way to know the reason of the failure in this case.
     *
     * @param integer $mode      The mode of the directory (ignored on Windows).
     * @param boolean $recursive Whether to create parent directories if they do not exist.
     *
     * @return boolean True if the directory was successfully created, false otherwise.
     */
    public function tryCreateDirectory($mode = 0777, $recursive = false)
    {
        return $this->swallow(E_WARNING, false, function() use ($mode, $recursive) {
            return mkdir($this->path, $mode, $recursive);
        });
    }

    /**
     * Writes data to the file pointed to by this path.
     *
     * If the file does not exist, it will be created.
     *
     * The following flags can be used, joined with the binary OR (`|`) operator:
     *
     * * FILE_USE_INCLUDE_PATH: Search for `path` in the include directory.
     * * FILE_APPEND:           If `path` already exists, append the data to the file instead of overwriting it.
     * * LOCK_EX:               Acquire an exclusive lock on the file while proceeding to the writing.
     *
     * @param string|resource $data  The data to write to the file.
     * @param integer         $flags A combination of zero or more flags.
     * @param integer         $bytes An optional variable to store the number of bytes written.
     *
     * @return Path The current path.
     *
     * @throws FileSystemException If an error occurs.
     */
    public function write($data, $flags = 0, & $bytes = 0)
    {
        $bytes = $this->swallow(E_WARNING, true, function() use ($data, $flags) {
            return file_put_contents($this->path, $data, $flags);
        });

        return $this;
    }

    /**
     * Reads the contents of the file pointed to by this path.
     *
     * @return string The file contents.
     *
     * @throws FileSystemException If an error occurs.
     */
    public function read()
    {
        return $this->swallow(E_WARNING, true, function() {
            return file_get_contents($this->path);
        });
    }

    /**
     * Deletes the file, link or directory pointed to by this path.
     *
     * If the path points to a directory then the directory must be empty,
     * or the `$recursive` parameter must be `true`, otherwise an exception will be thrown.
     *
     * @param boolean $recursive Whether to delete recursively a directory.
     *
     * @return Path The current path.
     *
     * @return FileSystemException If the file does not exist, directory is not empty, or an error occurs.
     */
    public function delete($recursive = false)
    {
        if ($recursive && $this->isDirectory()) {
            $files = new FSI($this->path, FSI::CURRENT_AS_PATHNAME | FSI::SKIP_DOTS);

            foreach ($files as $pathName) {
                (new Path($pathName))->delete(true);
            }
        }

        $this->swallow(E_WARNING, true, function() {
            is_dir($this->path) ? rmdir($this->path) : unlink($this->path);
        });

        return $this;
    }

    /**
     * Attempts to delete the file, link or directory pointed to by this path.
     *
     * If this path points to a directory then the directory must be empty.
     *
     * @return boolean True if the file was successfully deleted, false otherwise.
     */
    public function tryDelete()
    {
        return $this->swallow(E_WARNING, false, function() {
            return is_dir($this->path) ? rmdir($this->path) : unlink($this->path);
        });
    }

    /**
     * Moves the file, link or directory to the given path.
     *
     * If the target file exists, it will be overwritten.
     *
     * @param string $path The target file path.
     *
     * @return Path The path to the new file.
     *
     * @throws FileSystemException If an error occurs.
     */
    public function moveTo($path)
    {
        $this->swallow(E_WARNING, true, function() use ($path) {
            rename($this->path, $path);
        });

        return new Path($path);
    }

    /**
     * Attempts to move or rename the file, link or directory.
     *
     * If the target file exists, it will be overwritten.
     *
     * @param string $path The target file path.
     *
     * @return boolean True if the file was successfully moved, false otherwise.
     */
    public function tryMove($path)
    {
        return $this->swallow(E_WARNING, false, function() use ($path) {
            return rename($this->path, $path);
        });
    }

    /**
     * Copies the file to the given path.
     *
     * If this path points to a symbolic link, it will be resolved, and the target file will be copied.
     * If the target file exists, it will be overwritten.
     *
     * @param string $path The target file path.
     *
     * @return Path The path to the new file.
     *
     * @throws FileSystemException If an error occurs.
     */
    public function copyTo($path)
    {
        $this->swallow(E_WARNING, true, function() use ($path) {
            copy($this->path, $path);
        });

        return new Path($path);
    }

    /**
     * Attempts to copy a file, and returns the success as a boolean.
     *
     * If this path points to a symbolic link, it will be resolved, and the target file will be copied.
     * If the target file exists, it will be overwritten.
     *
     * @param string $path The target file path.
     *
     * @return boolean True if the file was successfully copied, false otherwise.
     */
    public function tryCopyTo($path)
    {
        return $this->swallow(E_WARNING, false, function() use ($path) {
            return copy($this->path, $path);
        });
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->path;
    }

    /**
     * @param integer  $severity The severity of the errors to catch.
     * @param boolean  $throw    Whether to throw exceptions or silently ignore errors.
     * @param callable $function The function to call.
     *
     * @return mixed The return value of the called function.
     *
     * @throws FileSystemException If an error is caught and $throw is true.
     */
    private function swallow($severity, $throw, callable $function)
    {
        if (Path::$errorHandler === null) {
            Path::$errorHandler = new ErrorHandler(function (\ErrorException $e) {
                if (Path::$throw) {
                    throw FileSystemException::wrap($e);
                }
            });
        }

        Path::$throw = $throw;

        return Path::$errorHandler->swallow($severity, $function);
    }
}
