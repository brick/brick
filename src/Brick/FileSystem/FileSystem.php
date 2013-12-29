<?php

namespace Brick\FileSystem;

use Brick\ErrorHandler;

/**
 * Utility class for filesystem calls.
 *
 * This class is a thin wrapper around native filesystem function calls,
 * to provide a consistent API that catches PHP errors and throws proper exceptions when an operation fails.
 */
class FileSystem
{
    /**
     * @var \Brick\ErrorHandler
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
    public function realPath($path)
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
            $flags = \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::CURRENT_AS_PATHNAME;
            $files = new \FilesystemIterator($path, $flags);

            foreach ($files as $file) {
                $this->delete($file, true);
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
     * Moves or renames a file to a target file.
     *
     * The file will be moved between directories if necessary.
     * If `target` exists, it will be overwritten.
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
     * Attempts to move a file, and returns the success as a boolean.
     *
     * The file will be moved between directories if necessary.
     * If `target` exists, it will be overwritten.
     *
     * @param string $source The source file path.
     * @param string $target The target file path.
     *
     * @return boolean True if the file was successfully renamed, false otherwise.
     */
    public function tryMove($source, $target)
    {
        $this->throw = false;

        return $this->errorHandler->swallow(E_WARNING, function() use ($source, $target) {
            return rename($source, $target);
        });
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
