<?php

namespace Brick\Application\Plugin;

use Brick\Application\Event\ControllerReadyEvent;
use Brick\Application\Event\ResponseReceivedEvent;
use Brick\Application\Plugin;
use Brick\Application\Controller\Interfaces\OnRequestInterface;
use Brick\Application\Controller\Interfaces\OnResponseInterface;
use Brick\Event\EventDispatcher;

/**
 * Calls `onRequest()` and `onResponse()` on controllers implementing OnRequestInterface and OnResponseInterface.
 */
class OnRequestResponsePlugin implements Plugin
{
    /**
     * {@inheritdoc}
     */
    public function register(EventDispatcher $dispatcher)
    {
        $dispatcher->addListener(ControllerReadyEvent::class, function(ControllerReadyEvent $event) {
            $controller = $event->getControllerInstance();

            if ($controller instanceof OnRequestInterface) {
                $controller->onRequest($event->getRequest());
            }
        });

        $dispatcher->addListener(ResponseReceivedEvent::class, function(ResponseReceivedEvent $event)
        {
            $controller = $event->getControllerInstance();

            if ($controller instanceof OnResponseInterface) {
                $controller->onResponse($event->getResponse());
            }
        });
    }
}
