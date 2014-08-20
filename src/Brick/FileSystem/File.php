<?php

namespace Brick\FileSystem;

use Brick\Error\ErrorHandler;

/**
 * Object-oriented wrapper around native file functions.
 *
 * This class is a thin layer above native filesystem function calls,
 * to provide a consistent object API that catches PHP errors and
 * throws proper exceptions when an operation fails.
 */
class File
{
    /**
     * @var resource
     */
    private $handle;

    /**
     * @var boolean
     */
    private $throw;

    /**
     * @var \Brick\Error\ErrorHandler
     */
    private $errorHandler;

    /**
     * Class constructor.
     *
     * @param string $path The file path.
     * @param string $mode The open mode. See the documentation for fopen() for available modes.
     *
     * @throws FileSystemException If the file cannot be open.
     */
    public function __construct($path, $mode)
    {
        $this->errorHandler = new ErrorHandler(function (\ErrorException $e) {
            if ($this->throw) {
                throw FileSystemException::wrap($e);
            }
        });

        $this->throw = true;

        $this->errorHandler->swallow(E_WARNING, function () use ($path, $mode) {
            $this->handle = fopen($path, $mode);
        });
    }

    /**
     * Class destructor.
     */
    public function __destruct()
    {
        $this->throw = false;

        $this->errorHandler->swallow(E_WARNING, function() {
            fclose($this->handle);
        });
    }

    /**
     * Returns whether the end-of-file has been reached.
     *
     * @return boolean True if the file pointer is at EOF, False otherwise.
     *
     * @throws FileSystemException If an error occurs.
     */
    public function eof()
    {
        $this->throw = true;

        return $this->errorHandler->swallow(E_WARNING, function() {
            return feof($this->handle);
        });
    }

    /**
     * Reads from the file.
     *
     * If calling read() several times, the read will resume from the current position.
     * The pointer can be moved with seek().
     *
     * @param integer $length The maximum bytes to read. Defaults to -1 (read all the remaining buffer).
     *
     * @return string
     *
     * @throws FileSystemException
     */
    public function read($length = -1)
    {
        $this->throw = true;

        return $this->errorHandler->swallow(E_WARNING, function() use ($length) {
            return stream_get_contents($this->handle, $length);
        });
    }

    /**
     * Writes to the file.
     *
     * @param string $data The data to write.
     *
     * @return integer The number of bytes written.
     *
     * @throws FileSystemException
     */
    public function write($data)
    {
        $this->throw = true;

        return $this->errorHandler->swallow(E_WARNING, function() use ($data) {
            return fwrite($this->handle, $data);
        });
    }

    /**
     * Acquires a lock on the file.
     *
     * @param boolean $exclusive True to acquire an exclusive lock (default), or false to acquire a shared lock.
     *
     * @return void
     *
     * @throws FileSystemException If an error occurs.
     */
    public function lock($exclusive = true)
    {
        $this->throw = true;

        $this->errorHandler->swallow(E_WARNING, function() use ($exclusive) {
            flock($this->handle, $exclusive ? LOCK_SH : LOCK_EX);
        });
    }

    /**
     * Attempts to acquire a lock on the file.
     *
     * This method does not block. An invocation always returns immediately,
     * either having acquired a lock on the requested region or having failed to do so.
     *
     * @param boolean $exclusive True to acquire an exclusive lock (default), or false to acquire a shared lock.
     *
     * @return boolean Whether the lock was acquired.
     *
     * @throws FileSystemException If an error occurs.
     */
    public function tryLock($exclusive = true)
    {
        $this->throw = true;

        return $this->errorHandler->swallow(E_WARNING, function() use ($exclusive) {
            return flock($this->handle, ($exclusive ? LOCK_SH : LOCK_EX) | LOCK_NB);
        });
    }

    /**
     * Releases the lock acquired on the file.
     *
     * @return void
     *
     * @throws FileSystemException If an error occurs.
     */
    public function unlock()
    {
        $this->throw = true;

        $this->errorHandler->swallow(E_WARNING, function() {
            flock($this->handle, LOCK_UN);
        });
    }

    /**
     * Sets the position of the file read/write pointer.
     *
     * @param integer $offset The offset, measured in bytes.
     * @param integer $whence One of SEEK_SET, SEEK_CUR, SEEK_END.
     *
     * @return void
     *
     * @throws FileSystemException If an error occurs.
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        $this->throw = true;

        $this->errorHandler->swallow(E_WARNING, function() use ($offset, $whence) {
            fseek($this->handle, $offset, $whence);
        });
    }

    /**
     * Returns the current position of the file read/write pointer.
     *
     * @return integer The pointer position, measured in bytes.
     *
     * @throws FileSystemException If an error occurs.
     */
    public function tell()
    {
        $this->throw = true;

        return $this->errorHandler->swallow(E_WARNING, function() {
            return ftell($this->handle);
        });
    }

    /**
     * Truncates the file to a given length.
     *
     * If size is larger than the file then the file is extended with null bytes.
     * If size is smaller than the file then the file is truncated to that size.
     *
     * @param integer $size The size to truncate to.
     *
     * @return void
     *
     * @throws FileSystemException If an error occurs.
     */
    public function truncate($size)
    {
        $this->throw = true;

        $this->errorHandler->swallow(E_WARNING, function() use ($size) {
            ftruncate($this->handle, $size);
        });
    }

    /**
     * Forces the write of all buffered output to the file.
     *
     * @return void
     *
     * @throws FileSystemException If an error occurs.
     */
    public function flush()
    {
        $this->throw = true;

        $this->errorHandler->swallow(E_WARNING, function() {
            fflush($this->handle);
        });
    }
}
