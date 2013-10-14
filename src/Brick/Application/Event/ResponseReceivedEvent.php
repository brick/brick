<?php

namespace Brick\Application\Event;

use Brick\Routing\RouteMatch;
use Brick\Http\Request;
use Brick\Http\Response;

/**
 * Event dispatched after the controller response has been received.
 *
 * If an HttpException is caught during the controller method invocation,
 * the exception it is converted to a Response, and this event is dispatched as well.
 *
 * Other exceptions stop the application execution and don't trigger this event.
 */
class ResponseReceivedEvent extends AbstractControllerEvent
{
    /**
     * @var \Brick\Http\Response
     */
    private $response;

    /**
     * @param Request    $request
     * @param Response   $response
     * @param RouteMatch $routeMatch
     * @param object     $controller
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
