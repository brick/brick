<?php

namespace Brick\Session\Storage;

use Brick\FileSystem\File;
use Brick\FileSystem\FileSystem;
use Brick\FileSystem\Path;
use Brick\FileSystem\RecursiveFileIterator;

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
     * @todo Currently unused
     *
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
        $path = new Path($this->getPath($id, $key));

        if (! $path->exists()) {
            return null;
        }

        $file = $this->openFile($id, $key);

        $file->lock($lockContext);
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
            $this->fs->tryCreateDirectory($this->getPath($id));
            $file = $this->openFile($id, $key);
            $file->lock();
        }

        $file->truncate(0);
        $file->seek(0);
        $file->write($value);
        $file->unlock();
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
        $this->fs->tryDelete($this->getPath($id, $key));
    }

    /**
     * {@inheritdoc}
     */
    public function clear($id)
    {
        $this->fs->tryDelete($this->getPath($id));
    }

    /**
     * {@inheritdoc}
     */
    public function expire($lifetime)
    {
        /** @var \SplFileInfo[] $files */
        $files = new RecursiveFileIterator($this->directory);

        foreach ($files as $file) {
            if ($file->isFile() && $file->getATime() < time() - $lifetime) {
                $this->fs->tryDelete($file);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function updateId($oldId, $newId)
    {
        return $this->fs->tryMove(
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
     * @throws \Brick\FileSystem\FileSystemException
     */
    private function openFile($id, $key)
    {
        return new File($this->getPath($id, $key), 'cb+');
    }

    /**
     * @param string $char
     *
     * @return bool
     */
    private function isSafeAsciiChar($char)
    {
        $charBlacklist = ' \/:*?"<>|';

        $ord = ord($char);

        if ($ord <= 32 || $ord >= 127) {
            return false;
        }

        if (strpos($charBlacklist, $char) !== false) {
            return false;
        }

        return true;
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    private function sanitize($fileName)
    {
        $escapeChar = '%';

        $result = '';
        $length = strlen($fileName);

        for ($i = 0; $i < $length; $i++) {
            $char = $fileName[$i];

            if ($char === $escapeChar || ! $this->isSafeAsciiChar($char)) {
                $result .= $escapeChar . bin2hex($char);
            } else {
                $result .= $char;
            }
        }

        return $result;
    }

    /**
     * @param string $id
     * @param string $key
     *
     * @return string
     */
    private function getPath($id, $key = null)
    {
        $directoryPath = $this->directory . DIRECTORY_SEPARATOR . $this->sanitize($id);

        if ($key === null) {
            return $directoryPath;
        }

        return $directoryPath . DIRECTORY_SEPARATOR . $this->sanitize($key);
    }
}
