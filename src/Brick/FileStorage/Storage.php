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
     * @throws Exception\StorageException If an unknown error occurs.
     */
    public function put($path, $data);

    /**
     * @param string $path
     *
     * @return string
     *
     * @throws Exception\NotFoundException If the path is not found.
     * @throws Exception\StorageException  If an unknown error occurs.
     */
    public function get($path);

    /**
     * @param string $path
     *
     * @return void
     *
     * @throws Exception\StorageException If an unknown error occurs.
     */
    public function delete($path);
}
