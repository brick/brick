<?php

namespace Brick\FileStorage;

use Brick\Std\Io\FileSystem;
use Brick\Std\Io\IoException;

/**
 * Filesystem implementation of the Storage interface.
 */
class LocalStorage implements Storage
{
    /**
     * @var string
     */
    private $directory;

    /**
     * Class constructor.
     *
     * @param string $directory
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $directory)
    {
        if (! file_exists($directory) || ! is_dir($directory)) {
            throw new \InvalidArgumentException('Invalid directory: ' . $directory);
        }

        $this->directory = realpath($directory);
    }

    /**
     * @param string $path
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function getAbsolutePath(string $path) : string
    {
        // Explode the path into an array
        $path = str_replace('\\', '/', $path);
        $path = preg_split('|/|', $path, -1, PREG_SPLIT_NO_EMPTY);

        // Check that the path does not contain relative folders
        foreach ($path as $element) {
            if ($element == '.' || $element == '..') {
                throw new \InvalidArgumentException('The path cannot contain . or ..');
            }
        }

        // Prepend the base directory
        array_unshift($path, $this->directory);

        return implode(DIRECTORY_SEPARATOR, $path);
    }

    /**
     * {@inheritdoc}
     */
    public function put(string $path, string $data) : void
    {
        $path = $this->getAbsolutePath($path);
        FileSystem::createDirectories(dirname($path));

        try {
            FileSystem::write($path, $data);
        } catch (IoException $e) {
            throw Exception\StorageException::putError($path, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $path) : string
    {
        try {
            $path = $this->getAbsolutePath($path);

            if (! FileSystem::exists($path)) {
                throw Exception\NotFoundException::pathNotFound($path);
            }

            return FileSystem::read($path);
        } catch (IoException $e) {
            throw Exception\StorageException::getError($path, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $path) : bool
    {
        $path = $this->getAbsolutePath($path);

        return FileSystem::exists($path);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $path) : void
    {
        try {
            $path = $this->getAbsolutePath($path);

            if (! FileSystem::exists($path)) {
                throw Exception\NotFoundException::pathNotFound($path);
            }

            FileSystem::delete($path);
        } catch (IoException $e) {
            throw Exception\StorageException::deleteError($path, $e);
        }
    }
}
