<?php

namespace Brick\Routing\Route;

use Brick\Http\Request;
use Brick\Routing\Route;
use Brick\Routing\RouteMatch;

class CatchAllRoute implements Route
{
    /**
     * @var \Brick\Routing\RouteMatch
     */
    private $routeMatch;

    /**
     * @param RouteMatch $routeMatch
     */
    public function __construct(RouteMatch $routeMatch)
    {
        $this->routeMatch = $routeMatch;
    }

    /**
     * {@inheritdoc}
     */
    public function match(Request $request)
    {
        return $this->routeMatch;
    }
}
