<?php

namespace Brick\Application\Event;

use Brick\Http\Request;
use Brick\Routing\RouteMatch;

/**
 * Base class for events having a Request and a RouteMatch.
 */
abstract class AbstractRouteMatchEvent extends AbstractRequestEvent
{
    /**
     * @var \Brick\Routing\RouteMatch
     */
    private $routeMatch;

    /**
     * @param \Brick\Http\Request $request
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
