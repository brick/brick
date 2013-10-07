<?php

namespace Brick\Application\Event;

use Brick\Event\Event;
use Brick\Http\Exception\HttpException;
use Brick\Http\Request;
use Brick\Http\Response;

class ExceptionCaughtEvent extends Event
{
    /**
     * @var \Brick\Http\Exception\HttpException
     */
    private $exception;

    /**
     * @var \Brick\Http\Request
     */
    private $request;

    /**
     * @var \Brick\Http\Response
     */
    private $response;

    /**
     * @param \Brick\Http\Exception\HttpException $exception
     * @param \Brick\Http\Request                 $request
     * @param \Brick\Http\Response                $response
     */
    public function __construct(HttpException $exception, Request $request, Response $response)
    {
        $this->exception = $exception;
        $this->request   = $request;
        $this->response  = $response;
    }

    /**
     * @return \Brick\Http\Exception\HttpException
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @return \Brick\Http\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return \Brick\Http\Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
