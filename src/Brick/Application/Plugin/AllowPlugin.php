<?php

namespace Brick\Application\Plugin;

use Brick\Application\Event\RouteMatchEvent;
use Brick\Application\Events;
use Brick\Application\Controller\Annotation\Allow;
use Brick\Event\EventDispatcher;
use Brick\Http\Exception\HttpMethodNotAllowedException;

/**
 * Enforces the methods allowed on a controller with annotations.
 */
class AllowPlugin extends AbstractAnnotationPlugin
{
    /**
     * {@inheritdoc}
     */
    public function register(EventDispatcher $dispatcher)
    {
        $dispatcher->addListener(Events::ROUTE_MATCHED, [$this, 'checkRequestMethod']);
    }

    /**
     * @param RouteMatchEvent $event
     *
     * @return void
     */
    public function checkRequestMethod(RouteMatchEvent $event)
    {
        $controller = $event->getRouteMatch()->getControllerReflection();
        $annotation = $this->getControllerAnnotation($controller, Allow::class);

        if ($annotation instanceof Allow) {
            $method = $event->getRequest()->getMethod();
            $allowedMethods = $annotation->getMethods();

            if (! in_array($method, $allowedMethods)) {
                throw new HttpMethodNotAllowedException($allowedMethods);
            }
        }
    }
}
