<?php

namespace Brick\Routing;

/**
 * Exception thrown when an invalid controller has been returned by a router.
 */
class RoutingException extends \RuntimeException
{
    /**
     * @param string $class
     * @param string $method
     *
     * @return RoutingException
     */
    public static function invalidControllerClassMethod($class, $method)
    {
        return new self(sprintf(
            'Cannot find a controller method called %s::%s().',
            $class,
            $method
        ));
    }

    /**
     * @param mixed $function
     *
     * @return RoutingException
     */
    public static function invalidControllerFunction($function)
    {
        return new self(sprintf(
            'Invalid controller function: function name or closure expected, %s given.',
            is_string($function) ? '"' . $function . '"' : gettype($function)
        ));
    }
}
