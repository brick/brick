<?php

namespace Brick\Session\Storage;

use Brick\FileSystem\File;
use Brick\FileSystem\FileSystem;

/**
 * File storage engine for storing sessions on the filesystem.
 */
class FileStorage implements SessionStorage
{
    /**
     * @var string
     */
    private $directory;

    /**
     * @var integer
     */
    private $mode;

    /**
     * @var \Brick\FileSystem\FileSystem
     */
    private $fs;

    /**
     * Class constructor.
     *
     * @param string  $directory
     * @param integer $mode
     */
    public function __construct($directory, $mode = 0700)
    {
        $this->directory = $directory;
        $this->mode      = $mode;
        $this->fs        = new FileSystem();
    }

    /**
     * {@inheritdoc}
     */
    public function read($id, $key, & $lockContext)
    {
        $file = $this->openFile($id, $key);

        $lockContext ? $file->lockExclusive() : $file->lockShared();
        $data = $file->read();

        if ($lockContext) {
            // Keep the file locked and store the file object.
            $lockContext = $file;
        } else {
            // Unlock immediately and discard the file object.
            $file->unlock();
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function write($id, $key, $value, $lockContext)
    {
        if ($lockContext) {
            /** @var File $file */
            $file = $lockContext;
        } else {
            $file = $this->openFile($id, $key);
            $file->lockExclusive();
        }

        $file->truncate(0)->seek(0)->write($value)->unlock();
    }

    /**
     * {@inheritdoc}
     */
    public function unlock($lockContext)
    {
        /** @var File $lockContext */
        $lockContext->unlock();
    }

    /**
     * {@inheritdoc}
     */
    public function remove($id, $key)
    {
        $this->fs->remove($this->getPath($id, $key));
    }

    /**
     * {@inheritdoc}
     */
    public function clear($id)
    {
        $this->fs->remove($this->getPath($id));
    }

    /**
     * {@inheritdoc}
     */
    public function expire($lifetime)
    {
        $this->fs->foreachFile($this->directory, function(\SplFileInfo $file) use ($lifetime) {
            if ($file->getATime() < time() - $lifetime) {
                $this->fs->remove($file);
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function updateId($oldId, $newId)
    {
        return $this->fs->tryRename(
            $this->getPath($oldId),
            $this->getPath($newId)
        );
    }

    /**
     * Opens the session file for reading and writing, pointer at the beginning.
     *
     * @param string $id
     * @param string $key
     *
     * @return \Brick\FileSystem\File
     *
     * @throws \Brick\FileSystem\IoException
     */
    private function openFile($id, $key)
    {
        return new File($this->getPath($id, $key), 'cb+');
    }

    /**
     * @param string $id
     * @param string $key
     *
     * @return string
     */
    private function getPath($id, $key = null)
    {
        $directoryPath = $this->directory . DIRECTORY_SEPARATOR . $id;

        $this->fs->tryCreateDirectory($directoryPath);

        if ($key === null) {
            return $directoryPath;
        }

        return $directoryPath . DIRECTORY_SEPARATOR . sha1($key);
    }
}
