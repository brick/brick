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
     * @return string
     */
    public function toString();
}
