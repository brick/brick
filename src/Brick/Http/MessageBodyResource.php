<?php

namespace Brick\Http;

class MessageBodyResource implements MessageBody
{
    /**
     * @var resource
     */
    private $body;

    /**
     * @param resource $body
     */
    public function __construct($body)
    {
        $this->body = $body;
    }

    /**
     * {@inheritdoc}
     */
    public function read($length)
    {
        return stream_get_contents($this->body, $length);
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return stream_get_contents($this->body);
    }
}
