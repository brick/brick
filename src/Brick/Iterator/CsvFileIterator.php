<?php

namespace Brick\Iterator;

/**
 * Iterator to read CSV files.
 * Every line is returned as a 0-indexed array.
 */
class CsvFileIterator implements \Iterator
{
    /**
     * The file pointer resource.
     *
     * @var resource
     */
    protected $handle;

    /**
     * The field delimiter (one character only).
     *
     * @var string
     */
    protected $delimiter;

    /**
     * The field enclosure character (one character only).
     *
     * @var string
     */
    protected $enclosure;

    /**
     * The escape character (one character only).
     *
     * @var string
     */
    protected $escape;

    /**
     * The key of the current element (0-based).
     *
     * @var int
     */
    protected $key = 0;

    /**
     * The current element as a 0-indexed array, or null if end of file / error.
     *
     * @var array|null
     */
    protected $current;

    /**
     * Class constructor.
     *
     * @param string $file      The CSV file path.
     * @param string $delimiter The field delimiter (one character only).
     * @param string $enclosure The field enclosure character (one character only).
     * @param string $escape    The escape character (one character only).
     * @throws \InvalidArgumentException
     */
    public function __construct($file, $delimiter = ',', $enclosure = '"', $escape = '\\')
    {
        $this->delimiter = $this->checkCharacter($delimiter);
        $this->enclosure = $this->checkCharacter($enclosure);
        $this->escape    = $this->checkCharacter($escape);

        $this->handle = @fopen($file, 'r');

        if (! is_resource($this->handle)) {
            throw new \InvalidArgumentException('Cannot open file for reading: ' . $file);
        }

        $this->readCurrent();
    }

    /**
     * Checks that the delimiter, enclosure & escape strings are only one character.
     *
     * @param string $char
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function checkCharacter($char)
    {
        $char = (string) $char;
        if (strlen($char) != 1) {
            throw new \InvalidArgumentException('Delimiter, enclosure and escape strings can only be one character');
        }

        return $char;
    }

    /**
     * Reads the current CSV line.
     *
     * @return void
     */
    protected function readCurrent()
    {
        $current = @fgetcsv($this->handle, 0, $this->delimiter, $this->enclosure, $this->escape);
        $this->current = is_array($current) ? $current : null;
    }

    /**
     * Class destructor.
     */
    public function __destruct()
    {
        @fclose($this->handle);
    }

    /**
     * Rewinds the Iterator to the first element.
     *
     * If the stream does not support seeking, the iterator will appear empty after rewind().
     *
     * @return void
     */
    public function rewind()
    {
        @fseek($this->handle, 0);
        $this->readCurrent();
        $this->key = 0;
    }

    /**
     * Returns whether the current position is valid.
     *
     * @return bool
     */
    public function valid()
    {
        return is_array($this->current);
    }

    /**
     * Returns the key of the current element (0-based).
     *
     * @return int
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Returns the current element, or null if end of file / error.
     *
     * @return array|null
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * Move forward to next element.
     *
     * @return void
     */
    public function next()
    {
        $this->readCurrent();
        $this->key++;
    }
}
