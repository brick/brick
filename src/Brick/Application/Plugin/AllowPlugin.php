<?php

namespace Brick\Application\Plugin;

use Brick\Application\Event\RouteMatchedEvent;
use Brick\Application\Controller\Annotation\Allow;
use Brick\Event\EventDispatcher;
use Brick\Http\Exception\HttpMethodNotAllowedException;

/**
 * Enforces the methods allowed on a controller with the Allow annotation.
 */
class AllowPlugin extends AbstractAnnotationPlugin
{
    /**
     * {@inheritdoc}
     */
    public function register(EventDispatcher $dispatcher)
    {
        $dispatcher->addListener(RouteMatchedEvent::class, function(RouteMatchedEvent $event) {
            $controller = $event->getRouteMatch()->getControllerReflection();
            $annotation = $this->getControllerAnnotation($controller, Allow::class);

            if ($annotation instanceof Allow) {
                $method = $event->getRequest()->getMethod();
                $allowedMethods = $annotation->getMethods();

                if (! in_array($method, $allowedMethods)) {
                    throw new HttpMethodNotAllowedException($allowedMethods);
                }
            }
        });
    }
}
