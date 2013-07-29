<?php

namespace Brick\Event;

/**
 * Interface that all event listeners must implement.
 */
interface EventListener
{
    /**
     * @param Event $event
     * @return void
     */
    public function handleEvent(Event $event);

    /**
     * The listener priority. The higher the number, the higher the priority.
     *
     * @return int
     */
    public function getPriority();
}
