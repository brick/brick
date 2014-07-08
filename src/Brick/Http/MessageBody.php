<?php

namespace Brick\Http;

interface MessageBody
{
    /**
     * @param integer $length
     *
     * @return string
     */
    public function read($length);

    /**
     * Returns the size of the body if known.
     *
     * @return integer|null The size in bytes if known, or null if unknown.
     */
    public function getSize();

    /**
     * @return string
     */
    public function __toString();
}
