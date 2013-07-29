<?php

namespace Brick\Browser\Exception;

class NoSuchElementException extends BrowserException
{
    /**
     * @return NoSuchElementException
     */
    public static function emptyList()
    {
        return new self('The element list is empty.');
    }

    /**
     * @param int $index
     * @return NoSuchElementException
     */
    public static function undefinedIndex($index)
    {
        return new self(sprintf('Element with index "%s" does not exist', $index));
    }
}
