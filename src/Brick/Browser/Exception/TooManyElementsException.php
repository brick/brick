<?php

namespace Brick\Browser\Exception;

class TooManyElementsException extends BrowserException
{
    /**
     * @param int $count
     * @return TooManyElementsException
     */
    public static function expectedOne($count)
    {
        return new self(sprintf('Expected 1 element, found %d.', $count));
    }
}
