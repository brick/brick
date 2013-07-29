<?php

namespace Brick\Event;

/**
 * Dispatches events to registered listeners.
 */
class EventDispatcher
{
    /**
     * @var EventListener[]
     */
    private $listeners = [];

    /**
     * Adds a listener. If the listener is already registered, this method does nothing.
     *
     * @param EventListener $listener
     *
     * @return EventDispatcher
     */
    public function addListener(EventListener $listener)
    {
        $hash = spl_object_hash($listener);
        $this->listeners[$hash] = $listener;

        return $this;
    }

    /**
     * Removes a listener. If the listener is not registered, this method does nothing.
     *
     * @param EventListener $listener
     *
     * @return EventDispatcher
     */
    public function removeListener(EventListener $listener)
    {
        $hash = spl_object_hash($listener);
        unset($this->listeners[$hash]);

        return $this;
    }

    /**
     * Dispatches the given event to the registered listeners.
     *
     * The highest priority listeners will be called first.
     * If two listeners have the same priority, the first registered will be called first.
     *
     * @param Event $event
     *
     * @return EventDispatcher
     */
    public function dispatch(Event $event)
    {
        foreach ($this->getOrderedListeners() as $listener) {
            if ($event->isPropagationStopped()) {
                break;
            }

            $listener->handleEvent($event);
        }

        return $this;
    }

    /**
     * Returns the listeners, in the order they have to be called.
     *
     * @return EventListener[]
     */
    private function getOrderedListeners()
    {
        $listeners = [];
        $index = $count = count($this->listeners);

        foreach ($this->listeners as $listener) {
            $priority = $listener->getPriority();
            $listeners[$priority * $count + --$index] = $listener;
        }

        krsort($listeners);

        return $listeners;
    }
}
