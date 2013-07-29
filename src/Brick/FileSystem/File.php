<?php

namespace Brick\FileSystem;

/**
 * Object-oriented wrapper around native file functions.
 *
 * All PHP errors are muted, instead an exception is throw when an error occurs.
 */
class File
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @var resource
     */
    private $handle;

    /**
     * Class constructor.
     *
     * @param string $filename
     * @param string $mode
     *
     * @throws IoException
     */
    public function __construct($filename, $mode)
    {
        $this->filename = $filename;
        $this->handle = @ fopen($filename, $mode);

        if ($this->handle === false) {
            throw new IoException('Cannot open file ' . $filename);
        }
    }

    /**
     * Class destructor.
     */
    public function __destruct()
    {
        @ fclose($this->handle);
    }

    /**
     * @return boolean
     */
    public function eof()
    {
        return @ feof($this->handle);
    }

    /**
     * Reads the file, optionally up to a given length.
     *
     * If calling read() several times, the read will resume from the current position.
     * The pointer can be moved with seek().
     *
     * @param integer|null $maxLength The maximum number of bytes to read, or null to read all.
     *
     * @return string
     *
     * @throws IoException
     */
    public function read($maxLength = null)
    {
        if ($maxLength === null) {
            $data = @ stream_get_contents($this->handle);
        } else {
            $data = @ fread($this->handle, $maxLength);
        }

        if ($data === false) {
            throw new IoException('Error while reading file ' . $this->filename);
        }

        return $data;
    }

    /**
     * @param string $data The data to write.
     *
     * @return File
     *
     * @throws IoException
     */
    public function write($data)
    {
        $length = @ fwrite($this->handle, $data);

        if ($length === false) {
            throw new IoException('Error while writing to file ' . $this->filename);
        }

        return $this;
    }

    /**
     * @param boolean $blocking Whether to block while waiting for the lock. Defaults to true.
     *
     * @return boolean Whether the lock was acquired. Always true in blocking mode.
     *
     * @throws IoException
     */
    public function lockShared($blocking = true)
    {
        return $this->doLock(LOCK_SH, $blocking);
    }

    /**
     * @param boolean $blocking Whether to block while waiting for the lock. Defaults to true.
     *
     * @return boolean Whether the lock was acquired. Always true in blocking mode.
     *
     * @throws IoException
     */
    public function lockExclusive($blocking = true)
    {
        return $this->doLock(LOCK_EX, $blocking);
    }

    /**
     * @param integer $operation The operation, LOCK_SH or LOCK_EX.
     * @param boolean $blocking  Whether to block while waiting for the lock.
     *
     * @return boolean
     *
     * @throws IoException
     */
    private function doLock($operation, $blocking)
    {
        if (! $blocking) {
            $operation |= LOCK_NB;
        }

        $result = @ flock($this->handle, $operation, $wouldblock);

        if ($result === false && $wouldblock !== 1) {
            // Windows returns false when a non-blocking lock is not available,
            // but leaves $wouldblock at zero. There is no way to know whether the failure
            // is just temporary or permanent.
            if (! $this->isWindows()) {
                throw new IoException('Error trying to acquire a lock on ' . $this->filename);
            }
        }

        return $result;
    }

    /**
     * @return File
     *
     * @throws IoException
     */
    public function unlock()
    {
        $result = @ flock($this->handle, LOCK_UN);

        if ($result === false) {
            throw new IoException('Could not release a lock on ' . $this->filename);
        }

        return $this;
    }

    /**
     * @param integer $offset
     * @param integer $whence
     *
     * @return File
     *
     * @throws IoException
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        $result = @ fseek($this->handle, $offset, $whence);

        if ($result !== 0) {
            throw new IoException('Error while seeking on file ' . $this->filename);
        }

        return $this;
    }

    /**
     * @return integer
     *
     * @throws IoException
     */
    public function tell()
    {
        $offset = @ ftell($this->handle);

        if ($offset === false) {
            throw new IoException('Error while getting offset with fseek() on file ' . $this->filename);
        }

        return $offset;
    }

    /**
     * @param integer $size
     *
     * @return File
     *
     * @throws IoException
     */
    public function truncate($size)
    {
        $result = @ ftruncate($this->handle, $size);

        if ($result === false) {
            throw new IoException('Error while truncating file ' . $this->filename);
        }

        return $this;
    }

    /**
     * @return File
     *
     * @throws IoException
     */
    public function flush()
    {
        $result = @ fflush($this->handle);

        if ($result === false) {
            throw new IoException('Could not flush() the file ' . $this->filename);
        }

        return $this;
    }

    /**
     * @return boolean
     */
    private function isWindows()
    {
        return strtolower(substr(PHP_OS, 0, 3)) == 'win';
    }
}
