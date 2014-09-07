<?php

namespace Brick\Application\Event;

use Brick\Http\Request;
use Brick\Http\Response;
use Brick\Routing\RouteMatch;

/**
 * Extends the ControllerEvent with a Response.
 */
class ResponseEvent extends ControllerEvent
{
    /**
     * @var \Brick\Http\Response
     */
    private $response;

    /**
     * @param Request     $request
     * @param Response    $response
     * @param RouteMatch  $routeMatch
     * @param object|null $controller
     */
    public function __construct(Request $request, Response $response, RouteMatch $routeMatch, $controller)
    {
        parent::__construct($request, $routeMatch, $controller);

        $this->response = $response;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
