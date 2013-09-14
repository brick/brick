<?php

namespace Brick\Http;

/**
 * Represents the HTTP request body.
 * It is built from a non-seekable stream, typically the php://input stream.
 */
class RequestBody
{
    /**
     * @var resource
     */
    private $stream;

    /**
     * @var integer
     */
    private $contentLength;

    /**
     * @var string|null
     */
    private $content = null;

    /**
     * @var boolean
     */
    private $readStarted = false;

    /**
     * Class constructor.
     *
     * @param resource $stream
     * @param integer  $contentLength
     *
     * @throws \Brick\Http\RequestBodyException
     */
    public function __construct($stream, $contentLength)
    {
        $this->stream = $stream;
        $this->contentLength = $contentLength;

        if (ftell($this->stream) != 0) {
            throw RequestBodyException::streamPointerNotAtBeginning();
        }
    }

    /**
     * @return boolean
     */
    public function isEmpty()
    {
        return $this->toString() !== '';
    }

    /**
     * @return string
     */
    public function toString()
    {
        if ($this->content === null) {
            $this->content = stream_get_contents($this->stream);
        }

        return $this->content;
    }
}
