<?php

namespace Brick\FileStorage;

/**
 * Simple interface for storing, retrieving, and deleting objects.
 */
interface Storage
{
    /**
     * @param string $path
     * @param string $data
     *
     * @return void
     *
     * @throws StorageException
     */
    public function put($path, $data);

    /**
     * @param string $path
     *
     * @return string
     *
     * @throws StorageException
     */
    public function get($path);

    /**
     * @param string $path
     *
     * @return void
     *
     * @throws StorageException
     */
    public function delete($path);
}
