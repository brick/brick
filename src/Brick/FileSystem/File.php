<?php

namespace Brick\FileSystem;

use Brick\ErrorHandler;

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
     * @var \Brick\ErrorHandler
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
     * @return boolean
     *
     * @throws FileSystemException
     */
    public function eof()
    {
        $this->throw = true;

        return $this->errorHandler->swallow(E_WARNING, function() {
            return feof($this->handle);
        });
    }

    /**
     * Reads the file, optionally up to a given length.
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

        return $this->errorHandler->swallow(E_WARNING, function($length) {
            return stream_get_contents($this->handle, $length);
        });
    }

    /**
     * @param string $data The data to write.
     *
     * @return integer The number of bytes written.
     *
     * @throws FileSystemException
     */
    public function write($data)
    {
        $this->throw = true;

        return $this->errorHandler->swallow(E_WARNING, function($data) {
            return fwrite($this->handle, $data);
        });
    }

    /**
     * Locks the file in share mode.
     *
     * @param boolean $blocking Whether to block while waiting for the lock. Defaults to true.
     *
     * @return boolean Whether the lock was acquired. Always true in blocking mode.
     *
     * @throws FileSystemException If an error occurs.
     */
    public function lockShared($blocking = true)
    {
        return $this->doLock(LOCK_SH, $blocking);
    }

    /**
     * Locks the file in exclusive mode.
     *
     * @param boolean $blocking Whether to block while waiting for the lock. Defaults to true.
     *
     * @return boolean Whether the lock was acquired. Always true in blocking mode.
     *
     * @throws FileSystemException If an error occurs.
     */
    public function lockExclusive($blocking = true)
    {
        return $this->doLock(LOCK_EX, $blocking);
    }

    /**
     * @param integer $operation The operation, LOCK_SH or LOCK_EX.
     * @param boolean $blocking  Whether to block while waiting for the lock.
     *
     * @return boolean Whether the lock was successfully acquired.
     *
     * @throws FileSystemException If an error occurs.
     */
    private function doLock($operation, $blocking)
    {
        if (! $blocking) {
            $operation |= LOCK_NB;
        }

        $this->throw = true;

        return $this->errorHandler->swallow(E_WARNING, function() use ($operation) {
            return flock($this->handle, $operation);
        });
    }

    /**
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
     * @param integer $offset
     * @param integer $whence
     *
     * @return void
     *
     * @throws FileSystemException
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        $this->throw = true;

        $this->errorHandler->swallow(E_WARNING, function() use ($offset, $whence) {
            fseek($this->handle, $offset, $whence);
        });
    }

    /**
     * @return integer
     *
     * @throws FileSystemException
     */
    public function tell()
    {
        $this->throw = true;

        return $this->errorHandler->swallow(E_WARNING, function() {
            return ftell($this->handle);
        });
    }

    /**
     * @param integer $size
     *
     * @return void
     *
     * @throws FileSystemException
     */
    public function truncate($size)
    {
        $this->throw = true;

        $this->errorHandler->swallow(E_WARNING, function() use ($size) {
            ftruncate($this->handle, $size);
        });
    }

    /**
     * @return void
     *
     * @throws FileSystemException
     */
    public function flush()
    {
        $this->throw = true;

        $this->errorHandler->swallow(E_WARNING, function() {
            fflush($this->handle);
        });
    }
}
