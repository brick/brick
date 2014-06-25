<?php

namespace Brick\Http;

class MessageBodyString implements MessageBody
{
    /**
     * @var string
     */
    private $body;

    /**
     * @var integer
     */
    private $offset = 0;

    /**
     * @param string $body
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
        $string = substr($this->body, $this->offset, $length);

        $this->offset += $length;

        return (string) $string;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return $this->body;
    }
}
