<?php

namespace Brick\Application\Event;

use Brick\Routing\RouteMatch;
use Brick\Http\Request;

/**
 * Extends the RouteMatchEvent with an optional controller instance.
 */
class ControllerEvent extends RouteMatchEvent
{
    /**
     * The controller instance, or null if the controller is not a class method.
     *
     * @var object|null
     */
    private $instance;

    /**
     * @param \Brick\Http\Request       $request    The request.
     * @param \Brick\Routing\RouteMatch $routeMatch The route match.
     * @param object|null               $instance   The controller instance, or null if the controller is not a method.
     */
    public function __construct(Request $request, RouteMatch $routeMatch, $instance)
    {
        parent::__construct($request, $routeMatch);

        $this->instance = $instance;
    }

    /**
     * Returns the controller instance, or null if the controller is not a class method.
     *
     * @return object|null
     */
    public function getControllerInstance()
    {
        return $this->instance;
    }
}
