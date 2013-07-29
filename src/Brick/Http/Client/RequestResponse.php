<?php

namespace Brick\Http\Client;

use Brick\Http\Request;
use Brick\Http\Response;

/**
 * Stores a Request/Response pair.
 */
class RequestResponse
{
    /**
     * @var \Brick\Http\Request
     */
    private $request;

    /**
     * @var \Brick\Http\Response
     */
    private $response;

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
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
