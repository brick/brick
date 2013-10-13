<?php

namespace Brick\Event;

/**
 * Base class for event listeners with configurable priority.
 */
abstract class AbstractEventListener implements EventListener
{
    /**
     * @var integer
     */
    private $priority = 0;

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     *
     * @return static
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }
}
