<?php

namespace Brick\Application;

/**
 * Storage for parameters to resolve a controller.
 */
class ParameterMap
{
    /**
     * An associative array of key-value pairs controller parameters will resolve to.
     *
     * @var array
     */
    private $parameters = [];

    /**
     * Adds key-value pairs to resolve controller parameters.
     *
     * @param array $parameters An associative array of key-value pairs.
     *
     * @return void
     */
    public function addParameters(array $parameters)
    {
        $this->parameters = $parameters + $this->parameters;
    }

    /**
     * Returns values to be used to resolve controller parameters.
     *
     * @return array An associative array of key-value pairs.
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
