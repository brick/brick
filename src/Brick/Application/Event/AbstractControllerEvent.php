<?php

namespace Brick\Application\Event;

use Brick\Routing\RouteMatch;
use Brick\Http\Request;

/**
 * Base class for events having a Request, a RouteMatch, and an optional controller instance.
 */
abstract class AbstractControllerEvent extends AbstractRouteMatchEvent
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
