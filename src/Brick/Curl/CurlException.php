<?php

namespace Brick\Curl;

class CurlException extends \RuntimeException
{
    /**
     * @param string $error
     *
     * @return CurlException
     */
    public static function error($error)
    {
        return new self(sprintf('cURL request failed: %s.', $error));
    }
}
