<?php

namespace Brick\Application\Event;

use Brick\Http\Request;
use Brick\Routing\RouteMatch;

/**
 * Extends the RequestEvent with a RouteMatch.
 */
class RouteMatchEvent extends RequestEvent
{
    /**
     * @var \Brick\Routing\RouteMatch
     */
    private $routeMatch;

    /**
     * @param \Brick\Http\Request       $request
     * @param \Brick\Routing\RouteMatch $routeMatch
     */
    public function __construct(Request $request, RouteMatch $routeMatch)
    {
        parent::__construct($request);

        $this->routeMatch = $routeMatch;
    }

    /**
     * @return \Brick\Routing\RouteMatch
     */
    public function getRouteMatch()
    {
        return $this->routeMatch;
    }
}
