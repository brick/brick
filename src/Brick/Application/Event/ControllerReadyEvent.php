<?php

namespace Brick\Application\Event;

/**
 * Event dispatched when the controller is ready to be invoked.
 * If the controller is a class method, the controller is now instantiated, and available here.
 */
class ControllerReadyEvent extends AbstractControllerEvent
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
