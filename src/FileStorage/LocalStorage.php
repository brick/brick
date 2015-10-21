<?php

namespace Brick\FileStorage;

use Brick\FileSystem\FileSystem;
use Brick\FileSystem\FileSystemException;

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
     * @var \Brick\FileSystem\FileSystem
     */
    private $filesystem;

    /**
     * Class constructor.
     *
     * @param string $directory
     * @throws \InvalidArgumentException
     */
    public function __construct($directory)
    {
        if (! file_exists($directory) || ! is_dir($directory)) {
            throw new \InvalidArgumentException('Invalid directory: ' . $directory);
        }

        $this->directory = realpath($directory);
        $this->filesystem = new FileSystem();
    }

    /**
     * @param string $path
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function getAbsolutePath($path)
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
    public function put($path, $data)
    {
        $path = $this->getAbsolutePath($path);
        $this->filesystem->tryCreateDirectory(dirname($path), 0777, true);

        try {
            $this->filesystem->write($path, $data);
        } catch (FileSystemException $e) {
            throw Exception\StorageException::putError($path, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($path)
    {
        try {
            $path = $this->getAbsolutePath($path);

            if (! $this->filesystem->exists($path)) {
                throw Exception\NotFoundException::pathNotFound($path);
            }

            return $this->filesystem->read($path);
        } catch (FileSystemException $e) {
            throw Exception\StorageException::getError($path, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function has($path)
    {
        $path = $this->getAbsolutePath($path);

        return $this->filesystem->exists($path);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($path)
    {
        try {
            $path = $this->getAbsolutePath($path);

            if (! $this->filesystem->exists($path)) {
                throw Exception\NotFoundException::pathNotFound($path);
            }

            $this->filesystem->delete($path, true);
        } catch (FileSystemException $e) {
            throw Exception\StorageException::deleteError($path, $e);
        }
    }
}
