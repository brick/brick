<?php

namespace Brick\FileSystem;

use Brick\Error\ErrorHandler;
use FilesystemIterator as FSI;

/**
 * Utility class for filesystem calls.
 *
 * This class is a thin wrapper around native filesystem function calls,
 * to provide a consistent API that catches PHP errors and throws proper exceptions when an operation fails.
 */
class FileSystem
{
    /**
     * @var \Brick\Error\ErrorHandler
     */
    private $errorHandler;

    /**
     * @var boolean
     */
    private $throw = true;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->errorHandler = new ErrorHandler(function (\ErrorException $e) {
            if ($this->throw) {
                throw FileSystemException::wrap($e);
            }
        });
    }

    /**
     * Returns whether the given file or directory exists.
     *
     * @param string $path The path to test.
     *
     * @return boolean True if the path exists, false otherwise.
     *
     * @throws FileSystemException If an unexpected error occurs.
     */
    public function exists($path)
    {
        $this->throw = true;

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
     * @throws FileSystemException If the path does not exist or an unexpected error occurs.
     */
    public function getRealPath($path)
    {
        $path = realpath($path);

        if ($path === false) {
            throw FileSystemException::cannotGetRealPath($path);
        }

        return $path;
    }

    /**
     * Returns whether the given path is a directory.
     *
     * @param string $path The path to test.
     *
     * @return boolean True if the path exists and is a directory, false otherwise.
     *
     * @throws FileSystemException If an unknown error occurs.
     */
    public function isDirectory($path)
    {
        $this->throw = true;

        return $this->errorHandler->swallow(E_WARNING, function() use ($path) {
            return is_dir($path);
        });
    }

    /**
     * Returns whether the given path is a file.
     *
     * @param string $path The path to test.
     *
     * @return boolean True if the path exists and is a file, false otherwise.
     *
     * @throws FileSystemException If an unknown error occurs.
     */
    public function isFile($path)
    {
        $this->throw = true;

        return $this->errorHandler->swallow(E_WARNING, function() use ($path) {
            return is_file($path);
        });
    }

    /**
     * Returns whether the given path is a symbolic link.
     *
     * @param string $path The path to test.
     *
     * @return boolean True if the path exists and is a symbolic link, false otherwise.
     *
     * @throws FileSystemException If an error occurs.
     */
    public function isSymbolicLink($path)
    {
        $this->throw = true;

        return $this->errorHandler->swallow(E_WARNING, function() use ($path) {
            return is_link($path);
        });
    }

    /**
     * Creates a symbolic link to a target.
     *
     * @param string $target The target path.
     * @param string $link   The link path.
     *
     * @return void
     *
     * @throws FileSystemException If an error occurs.
     */
    public function createSymbolicLink($target, $link)
    {
        $this->throw = true;

        $this->errorHandler->swallow(E_WARNING, function() use ($target, $link) {
            symlink($target, $link);
        });
    }

    /**
     * Creates a new link (directory entry) for an existing file.
     *
     * @param string $target The target path.
     * @param string $link   The link path.
     *
     * @return void
     *
     * @throws FileSystemException If an error occurs.
     */
    public function createLink($target, $link)
    {
        $this->throw = true;

        $this->errorHandler->swallow(E_WARNING, function() use ($target, $link) {
            link($target, $link);
        });
    }

    /**
     * Returns the target of a symbolic link.
     *
     * @param string $path The symbolic link path.
     *
     * @return string The contents of the symbolic link path.
     *
     * @throws FileSystemException If the path does not exist, or is not a link.
     */
    public function readSymbolicLink($path)
    {
        $this->throw = true;

        return $this->errorHandler->swallow(E_WARNING, function() use ($path) {
            return readlink($path);
        });
    }

    /**
     * Creates a new directory.
     *
     * @param string  $path      The path of the directory to create.
     * @param integer $mode      The mode of the directory (ignored on Windows).
     * @param boolean $recursive Whether to create parent directories if they do not exist.
     *
     * @return void
     *
     * @throws FileSystemException If the directory already exists or cannot be created.
     */
    public function createDirectory($path, $mode = 0777, $recursive = false)
    {
        $this->throw = true;

        $this->errorHandler->swallow(E_WARNING, function() use ($path, $mode, $recursive) {
            mkdir($path, $mode, $recursive);
        });
    }

    /**
     * Attempts to create a new directory and returns the success as a boolean.
     *
     * Any error will be silently ignored and will make this method return false.
     * Note that there is no way to know the reason of the failure.
     *
     * @param string  $path      The path of the directory to create.
     * @param integer $mode      The mode of the directory (ignored on Windows).
     * @param boolean $recursive Whether to create parent directories if they do not exist.
     *
     * @return boolean True if the directory was successfully created, false otherwise.
     */
    public function tryCreateDirectory($path, $mode = 0777, $recursive = false)
    {
        $this->throw = false;

        return $this->errorHandler->swallow(E_WARNING, function() use ($path, $mode, $recursive) {
            return mkdir($path, $mode, $recursive);
        });
    }

    /**
     * Writes data to a given file.
     *
     * The following flags can be used, joined with the binary OR (`|`) operator:
     *
     * * FILE_USE_INCLUDE_PATH: Search for `path` in the include directory.
     * * FILE_APPEND:           If `path` already exists, append the data to the file instead of overwriting it.
     * * LOCK_EX:               Acquire an exclusive lock on the file while proceeding to the writing.
     *
     * @param string          $path  The file path.
     * @param string|resource $data  The data to write to the file.
     * @param integer         $flags A combination of zero or more flags.
     *
     * @return integer The number of bytes written.
     *
     * @throws FileSystemException If an error occurs while writing.
     */
    public function write($path, $data, $flags = 0)
    {
        $this->throw = true;

        return $this->errorHandler->swallow(E_WARNING, function() use ($path, $data, $flags) {
            return file_put_contents($path, $data, $flags);
        });
    }

    /**
     * @param string $path The file path.
     *
     * @return string The file contents.
     *
     * @throws FileSystemException If an error occurs while reading.
     */
    public function read($path)
    {
        $this->throw = true;

        return $this->errorHandler->swallow(E_WARNING, function() use ($path) {
            return file_get_contents($path);
        });
    }

    /**
     * Deletes a file or a directory.
     *
     * If the path points to a directory then the directory must be empty.
     *
     * @param string  $path      The file or directory path.
     * @param boolean $recursive Whether to delete recursively a directory.
     *                           If false, an exception will be thrown if trying to delete a non-empty directory.
     *
     * @return void
     *
     * @return FileSystemException If the file does not exist, or an error occurs.
     */
    public function delete($path, $recursive = false)
    {
        if ($recursive && $this->isDirectory($path)) {
            $files = new FSI($path, FSI::CURRENT_AS_PATHNAME | FSI::SKIP_DOTS);

            foreach ($files as $pathName) {
                $this->delete($pathName, true);
            }
        }

        $this->throw = true;

        $this->errorHandler->swallow(E_WARNING, function() use ($path) {
            is_dir($path) ? rmdir($path) : unlink($path);
        });
    }

    /**
     * Attempts to delete a file or a directory.
     *
     * If the path points to a directory then the directory must be empty.
     *
     * @param string $path The file or directory path.
     *
     * @return boolean True if the file was successfully deleted, false otherwise.
     */
    public function tryDelete($path)
    {
        $this->throw = false;

        return $this->errorHandler->swallow(E_WARNING, function() use ($path) {
            return is_dir($path) ? rmdir($path) : unlink($path);
        });
    }

    /**
     * Moves or renames a file.
     *
     * If the target file exists, it will be overwritten.
     *
     * @param string $source The source file path.
     * @param string $target The target file path.
     *
     * @return void
     *
     * @throws FileSystemException
     */
    public function move($source, $target)
    {
        $this->throw = true;

        $this->errorHandler->swallow(E_WARNING, function() use ($source, $target) {
            rename($source, $target);
        });
    }

    /**
     * Attempts to move or rename a file, and returns the success as a boolean.
     *
     * If the target file exists, it will be overwritten.
     *
     * @param string $source The source file path.
     * @param string $target The target file path.
     *
     * @return boolean True if the file was successfully moved, false otherwise.
     */
    public function tryMove($source, $target)
    {
        $this->throw = false;

        return $this->errorHandler->swallow(E_WARNING, function() use ($source, $target) {
            return rename($source, $target);
        });
    }

    /**
     * Copies a file.
     *
     * Symbolic links will be resolved, and the target file will be copied.
     * If the target file exists, it will be overwritten.
     *
     * @param string $source The source file path.
     * @param string $target The target file path.
     *
     * @return void
     *
     * @throws FileSystemException If an error occurs.
     */
    public function copy($source, $target)
    {
        $this->throw = true;

        $this->errorHandler->swallow(E_WARNING, function() use ($source, $target) {
            copy($source, $target);
        });
    }

    /**
     * Attempts to copy a file, and returns the success as a boolean.
     *
     * Symbolic links will be resolved, and the target file will be copied.
     * If the target file exists, it will be overwritten.
     *
     * @param string $source The source file path.
     * @param string $target The target file path.
     *
     * @return boolean True if the file was successfully copied, false otherwise.
     */
    public function tryCopy($source, $target)
    {
        $this->throw = false;

        return $this->errorHandler->swallow(E_WARNING, function() use ($source, $target) {
            return copy($source, $target);
        });
    }

    /**
     * Changes the current working directory.
     *
     * The current working directory is used to resolve relative paths.
     *
     * @param string $path The path to the new working directory.
     *
     * @return void
     *
     * @throws FileSystemException If the path is not a directory, or an error occurs.
     */
    public function changeWorkingDirectory($path)
    {
        $this->throw = true;

        $this->errorHandler->swallow(E_WARNING, function() use ($path) {
            chdir($path);
        });
    }

    /**
     * Returns the current working directory.
     *
     * @return string
     *
     * @throws FileSystemException If an error occurs.
     */
    public function getWorkingDirectory()
    {
        $path = getcwd();

        if ($path === false) {
            throw FileSystemException::cannotGetWorkingDirectory();
        }

        return $path;
    }

    /**
     * Creates a mirror copy of a file or directory.
     *
     * Directories will be copied recursively.
     * Symbolic links will be preserved.
     *
     * @param string $source The source file or directory path.
     * @param string $target The target path.
     *
     * @return void
     *
     * @throws FileSystemException If an error occurs.
     */
    public function mirror($source, $target)
    {
        if ($this->isDirectory($source)) {
            $files = new FSI($source, FSI::KEY_AS_FILENAME | FSI::CURRENT_AS_PATHNAME | FSI::SKIP_DOTS);

            foreach ($files as $fileName => $pathName) {
                $this->mirror($pathName, $target . DIRECTORY_SEPARATOR . $fileName);
            }
        } elseif ($this->isSymbolicLink($source)) {
            $this->createSymbolicLink($this->readSymbolicLink($source), $target);
        } else {
            $this->copy($source, $target);
        }
    }
}
