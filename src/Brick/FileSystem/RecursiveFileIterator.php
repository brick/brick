<?php

namespace Brick\FileSystem;

/**
 * Recursively crawls a directory looking for files.
 *
 * Each key represents the relative file path.
 * Each value is a SplFileInfo object.
 */
class RecursiveFileIterator implements \Iterator
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var \Iterator
     */
    private $iterator;

    /**
     * Class constructor.
     *
     * @param string $path The directory path.
     *
     * @throws FileSystemException If the path does not exist, is not a directory, or an error occurs.
     */
    public function __construct($path)
    {
        $this->path = $path;

        $flags = 0
            | \FilesystemIterator::KEY_AS_PATHNAME
            | \FilesystemIterator::CURRENT_AS_FILEINFO
            | \FilesystemIterator::SKIP_DOTS;

        try {
            $iterator = new \RecursiveDirectoryIterator($path, $flags);
        } catch (\UnexpectedValueException $e) {
            throw FileSystemException::wrap($e);
        }

        $this->iterator = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);
    }

    /**
     * Rewinds the Iterator to the first element.
     *
     * @return void
     */
    public function rewind()
    {
        $this->iterator->rewind();
    }

    /**
     * Returns whether the current position is valid.
     *
     * @return boolean
     */
    public function valid()
    {
        return $this->iterator->valid();
    }

    /**
     * Returns the relative path of the current file.
     *
     * @return integer
     */
    public function key()
    {
        return substr($this->iterator->key(), strlen($this->path) + 1);
    }

    /**
     * Returns the current element.
     *
     * @return \SplFileInfo
     *
     * @throws FileSystemException If called after the last element has been returned.
     */
    public function current()
    {
        try {
            return $this->iterator->current();
        }
        catch (\RuntimeException $e) {
            throw FileSystemException::wrap($e);
        }
    }

    /**
     * Moves forward to the next element.
     *
     * @return void
     */
    public function next()
    {
        $this->iterator->next();
    }
}
