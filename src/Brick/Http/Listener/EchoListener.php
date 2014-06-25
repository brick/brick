<?php

namespace Brick\Http\Listener;

use Brick\Http\Message;
use Brick\Http\Request;
use Brick\Http\Response;

class EchoListener implements MessageListener
{
    const REQUEST_HEADER  = 1;
    const REQUEST_BODY    = 2;
    const RESPONSE_HEADER = 4;
    const RESPONSE_BODY   = 8;

    const HEADER = 5;
    const BODY   = 10;

    const REQUEST  = 3;
    const RESPONSE = 12;

    const ALL = 15;

    /**
     * @var integer
     */
    private $mask;

    /**
     * @param integer $mask
     */
    public function __construct($mask = self::ALL)
    {
        $this->mask = $mask;
    }

    /**
     * {@inheritdoc}
     */
    public function listen(Message $message)
    {
        if ($message instanceof Request) {
            if ($this->mask & self::REQUEST_HEADER) {
                echo $message->getHeader();
            }

            if ($this->mask & self::REQUEST_BODY) {
                echo $body = $message->getBody()->toString();

                if ($body != '') {
                    echo Message::CRLF . Message::CRLF;
                }
            }
        }

        if ($message instanceof Response) {
            if ($this->mask & self::RESPONSE_HEADER) {
                echo $message->getHeader();
            }

            if ($this->mask & self::RESPONSE_BODY) {
                echo $body = $message->getBody()->toString();

                if ($body != '') {
                    echo Message::CRLF . Message::CRLF;
                }
            }
        }
    }
}
