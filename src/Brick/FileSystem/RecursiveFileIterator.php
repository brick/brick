<?php

namespace Brick\FileSystem;

/**
 * Recursively crawls a directory looking for files.
 *
 * Behaves like FilesystemIte
 *
 * Keys are the file path.
 * Values are SplFileInfo objects.
 */
class RecursiveFileIterator extends \FilterIterator
{
    /**
     * @var callable|null
     */
    private $filter;

    /**
     * Class constructor.
     *
     * @param string        $path   The file or directory path.
     * @param callable|null $filter A filter function.
     *
     * @throws FileSystemException If path does not exist, or an error occurs.
     */
    public function __construct($path, callable $filter = null)
    {
        $this->filter = $filter;

        $flags
            = \FilesystemIterator::KEY_AS_PATHNAME
            | \FilesystemIterator::CURRENT_AS_FILEINFO
            | \FilesystemIterator::SKIP_DOTS;

        try {
            $iterator = new \RecursiveDirectoryIterator($path, $flags);
        } catch (\UnexpectedValueException  $e) {
            throw FileSystemException::wrap($e);
        }

        $iterator = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);

        parent::__construct($iterator);
    }

    /**
     * {@inheritdoc}
     */
    public function accept()
    {
        if ($this->filter) {
            return call_user_func($this->filter, parent::current());
        }

        return true;
    }
}
