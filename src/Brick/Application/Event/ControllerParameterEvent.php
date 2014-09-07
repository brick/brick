<?php

namespace Brick\Application\Event;

/**
 * Extends the ControllerEvent with configurable parameters.
 */
class ControllerParameterEvent extends ControllerEvent
{
    /**
     * An associative array of key-value pairs controller parameters will resolve to.
     *
     * @var array
     */
    private $parameters = [];

    /**
     * Sets values to resolve controller parameters.
     *
     * @param array $parameters An associative array of key-value pairs.
     *
     * @return void
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
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
