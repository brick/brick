<?php

namespace Brick\Http;

use Brick\Type\Map;
use OutOfBoundsException;

/**
 * Represents a collection of UploadedFile instances, potentially nested in sub-arrays.
 * This object is immutable.
 */
class UploadedFileMap implements \IteratorAggregate
{
    /**
     * @var \Brick\Type\Map
     */
    private $items;

    /**
     * Class constructor.
     *
     * @param array $items An array of UploadedFile instances and/or such nested arrays.
     * @throws UploadedFileException If the array contains other types of variables.
     */
    public function __construct(array $items)
    {
        $this->checkItems($items);
        $this->items = new Map($items, true);
    }

    /**
     * Recursively check the items in the array.
     *
     * The array can only contain other arrays or UploadedFile instances.
     *
     * @param array $items
     *
     * @return void
     * @throws UploadedFileException
     */
    private function checkItems(array $items)
    {
        foreach ($items as $item) {
            if (is_array($item)) {
                $this->checkItems($item);
            } elseif (! $item instanceof UploadedFile) {
                $type = is_object($item) ? get_class($item) : gettype($item);
                throw new UploadedFileException('Expected UploadedFile or array, got ' . $type);
            }
        }
    }

    /**
     * Creates an UploadedFileMap from the $_FILES superglobal.
     *
     * @param array $files
     * @return UploadedFileMap
     */
    public static function createFromFilesGlobal(array $files)
    {
        $items = [];
        $files = self::normalizeSuperglobal($files);
        self::buildMap($files, $items);

        return new self($items);
    }

    /**
     * Normalizes a $_FILES superglobal.
     *
     * @param array $files
     * @return array
     */
    private static function normalizeSuperglobal(array $files)
    {
        $result = [];

        foreach ($files as $file) {
            $keys = array_keys($file);

            if (is_array($keys)) {
                foreach ($keys as $key) {
                    $result[$key] = [];
                    foreach ($files as $index => $file) {
                        $result[$key][$index] = $file[$key];
                    }
                }
            }

            // Only one of the entries was needed to get the PHP upload keys (name, tmp_name, etc.).
            break;
        }

        return $result;
    }

    /**
     * Recursively builds the map of UploadedFile instances.
     *
     * @param array $files
     * @param array $destination
     *
     * @return void
     */
    private static function buildMap(array $files, array & $destination)
    {
        foreach ($files as $structure) {
            foreach ($structure as $key => $value) {
                $subFiles = [];
                foreach ($files as $uploadKey => $data) {
                    $subFiles[$uploadKey] = $data[$key];
                }

                if (is_array($value)) {
                    $destination[$key] = [];
                    self::buildMap($subFiles, $destination[$key]);
                } else {
                    $destination[$key] = UploadedFile::createFromPhpArray($subFiles);
                }
            }

            // Only one of the entries was needed to get the structure.
            break;
        }
    }

    /**
     * Returns a single UploadedFile instance, matching the given path.
     *
     * @param string $path
     * @return UploadedFile
     * @throws \OutOfBoundsException If the path does not exist, or does not point to a single UploaedFile.
     * @throws \UnexpectedValueException If the path is malformed.
     */
    public function getFile($path)
    {
        $uploadedFile = $this->items->get($path);

        if (! $uploadedFile instanceof UploadedFile) {
            throw new OutOfBoundsException('The path does not map to a single UploadedFile');
        }

        return $uploadedFile;
    }

    /**
     * Returns an associative array of UploadedFile instances, matching the given path.
     *
     * @param string $path
     * @return UploadedFile[]
     * @throws \OutOfBoundsException If the path points to a single UploadedFile.
     * @throws \UnexpectedValueException If the path is malformed.
     */
    public function getFiles($path)
    {
        try {
            $items = $this->items->get($path);
        } catch (OutOfBoundsException $e) {
            return [];
        }

        if (! is_array($items)) {
            throw new OutOfBoundsException('The path does not map to an array of UploadedFile instances');
        }

        $uploadedFiles = [];
        foreach ($items as $index => $item) {
            if ($item instanceof UploadedFile) {
                $uploadedFiles[$index] = $item;
            }
        }

        return $uploadedFiles;
    }

    /**
     * Returns the map as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->items->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->items->getIterator();
    }
}
