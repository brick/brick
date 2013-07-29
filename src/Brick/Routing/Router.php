<?php

namespace Brick\Routing;

use Brick\Http\Request;
use Brick\Http\Exception\HttpNotFoundException;

/**
 * Matches a request against a set of routes.
 *
 * The router guarantees to return a RouteMatch, or to throw an exception.
 */
class Router
{
    /**
     * @var \Brick\Routing\Route[]
     */
    private $routes = array();

    /**
     * @param \Brick\Routing\Route $route
     *
     * @return \Brick\Routing\Router
     */
    public function addRoute(Route $route)
    {
        $this->routes[] = $route;

        return $this;
    }

    /**
     * Returns the first RouteMatch returned by any of the routes.
     *
     * The routes are matched in the order they have been registered.
     *
     * @param \Brick\Http\Request $request
     *
     * @return \Brick\Routing\RouteMatch
     *
     * @throws \Brick\Http\Exception\HttpNotFoundException If no route matches the request.
     * @throws \Brick\Http\Exception\HttpException         If any of the routes throws one.
     * @throws \UnexpectedValueException                   If any of the routes returns an unexpected value.
     */
    public function match(Request $request)
    {
        foreach ($this->routes as $route) {
            try {
                $match = $route->match($request);
            }
            catch (RoutingException $e) {
                throw new HttpNotFoundException($e->getMessage());
            }

            if ($match !== null) {
                if ($match instanceof RouteMatch) {
                    return $match;
                }

                throw new \UnexpectedValueException(sprintf(
                    'Unexpected value returned from route %s: %s is not a RouteMatch as expected.',
                    get_class($route),
                    is_object($match) ? get_class($match) : gettype($match)
                ));
            }
        }

        throw new HttpNotFoundException('No route matches the request.');
    }
}
