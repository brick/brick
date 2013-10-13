<?php

namespace Brick\Controller\EventListener;

use Brick\Event\Event;
use Brick\Application\Event\RouteMatchedEvent;
use Brick\Controller\Annotation\Allow;
use Brick\Http\Exception\HttpMethodNotAllowedException;

/**
 * Configures the methods allowed on a controller with annotations.
 */
class AllowListener extends AbstractAnnotationListener
{
    /**
     * {@inheritdoc}
     */
    public function handleEvent(Event $event)
    {
        if ($event instanceof RouteMatchedEvent) {
            $controller = $event->getRouteMatch()->getControllerReflection();
            $annotation = $this->getControllerAnnotation($controller, 'Brick\Controller\Annotation\Allow');

            if ($annotation instanceof Allow) {
                $method = $event->getRequest()->getMethod();
                $allowedMethods = $annotation->getMethods();

                if (! in_array($method, $allowedMethods)) {
                    throw new HttpMethodNotAllowedException($allowedMethods);
                }
            }
        }
    }
}
