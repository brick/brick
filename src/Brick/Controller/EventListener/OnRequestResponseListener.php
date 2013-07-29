<?php

namespace Brick\Controller\EventListener;

use Brick\Event\Event;
use Brick\Event\AbstractEventListener;
use Brick\Application\Event\ControllerReadyEvent;
use Brick\Application\Event\ResponseReceivedEvent;
use Brick\Controller\ControllerInterface\OnRequestInterface;
use Brick\Controller\ControllerInterface\OnResponseInterface;

/**
 * Listens to the application events to call onRequest() and onResponse() on controllers implementing them.
 */
class OnRequestResponseListener extends AbstractEventListener
{
    /**
     * {@inheritdoc}
     */
    public function handleEvent(Event $event)
    {
        if ($event instanceof ControllerReadyEvent) {
            $controller = $event->getControllerInstance();

            if ($controller instanceof OnRequestInterface) {
                $controller->onRequest($event->getRequest());
            }
        }

        if ($event instanceof ResponseReceivedEvent) {
            $controller = $event->getControllerInstance();

            if ($controller instanceof OnResponseInterface) {
                $controller->onResponse($event->getResponse());
            }
        }
    }
}
