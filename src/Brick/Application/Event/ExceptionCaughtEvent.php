<?php

namespace Brick\Application\Event;

use Brick\Event\Event;
use Brick\Http\Exception\HttpException;
use Brick\Http\Response;

class ExceptionCaughtEvent extends Event
{
    /**
     * @var \Brick\Http\Exception\HttpException
     */
    private $exception;

    /**
     * @var \Brick\Http\Response
     */
    private $response;

    /**
     * @param \Brick\Http\Exception\HttpException $exception
     * @param \Brick\Http\Response                $response
     */
    public function __construct(HttpException $exception, Response $response)
    {
        $this->exception = $exception;
        $this->response = $response;
    }

    /**
     * @return \Brick\Http\Exception\HttpException
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @return \Brick\Http\Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
