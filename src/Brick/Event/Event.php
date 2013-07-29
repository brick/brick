<?php

namespace Brick\Event;

/**
 * Base class that all events must extend.
 */
abstract class Event
{
    /**
     * @var bool
     */
    private $propagationStopped = false;

    /**
     * @return bool
     */
    public function isPropagationStopped()
    {
        return $this->propagationStopped;
    }

    /**
     * @return void
     */
    public function stopPropagation()
    {
        $this->propagationStopped = true;
    }
}
