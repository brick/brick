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
     * @var boolean
     */
    private $isEmpty;

    /**
     * @var boolean
     */
    private $isMissing;

    /**
     * @var string
     */
    private $firstByte;

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

        if (ftell($this->stream) != 0) {
            throw RequestBodyException::streamPointerNotAtBeginning();
        }

        // Read the first byte, to know whether the stream is empty.
        $this->firstByte = fread($stream, 1);

        $this->isEmpty = ($this->firstByte === '');
        $this->isMissing = $this->isEmpty && ($contentLength != 0);
    }

    /**
     * @return void
     *
     * @throws \Brick\Http\RequestBodyException
     */
    private function checkReadNotStarted()
    {
        if ($this->readStarted) {
            throw RequestBodyException::readAlreadyStarted();
        }
    }

    /**
     * @return boolean
     */
    public function isEmpty()
    {
        return $this->isEmpty;
    }

    /**
     * @param integer $length
     * @return string
     * @throws \InvalidArgumentException         If the length is not a positive integer.
     * @throws \Brick\Http\RequestBodyException If the request body is not available.
     */
    public function read($length)
    {
        if ($this->isMissing) {
            throw RequestBodyException::noRequestBody();
        }

        $length = (int) $length;

        if ($length <= 0) {
            throw new \InvalidArgumentException('Length parameter must be greater than 0');
        }

        $this->readStarted = true;
        $data = $this->firstByte;

        if ($this->firstByte !== '') {
            $this->firstByte = '';
            $length--;
        }

        while ($length != 0 && $this->hasMoreData()) {
            $block = fread($this->stream, $length);
            $data .= $block;
            $length -= strlen($block);
        }

        return $data;
    }

    /**
     * @return boolean
     */
    public function hasMoreData()
    {
        return ! feof($this->stream);
    }

    /**
     * @param resource $stream
     * @return void
     */
    public function copyToStream($stream)
    {
        $this->checkReadNotStarted();

        while ($this->hasMoreData()) {
            fwrite($stream, $this->read(8192));
        }
    }

    /**
     * @return string
     *
     * @throws \Brick\Http\RequestBodyException
     */
    public function toString()
    {
        if ($this->content !== null) {
            return $this->content;
        }

        if (! $this->hasMoreData()) {
            throw RequestBodyException::requestBodyAlreadyRead();
        }

        $this->checkReadNotStarted();
        $this->content = '';

        while ($this->hasMoreData()) {
            $this->content .= $this->read(8192);
        }

        return $this->content;
    }

    /**
     * @return array
     */
    public function getFormData()
    {
        parse_str($this->toString(), $data);

        return $data;
    }
}
