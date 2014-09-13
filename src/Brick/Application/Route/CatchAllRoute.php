<?php

namespace Brick\Application\Route;

use Brick\Http\Request;
use Brick\Application\Route;
use Brick\Application\RouteMatch;

class CatchAllRoute implements Route
{
    /**
     * @var \Brick\Application\RouteMatch
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
