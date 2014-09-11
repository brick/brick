<?php

namespace Brick\Application\Plugin;

use Brick\Application\Events;
use Brick\Application\Controller\Annotation\Allow;
use Brick\Event\EventDispatcher;
use Brick\Http\Exception\HttpMethodNotAllowedException;
use Brick\Http\Request;
use Brick\Routing\RouteMatch;

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
        $dispatcher->addListener(Events::ROUTE_MATCHED, [$this, 'checkRequestMethod']);
    }

    /**
     * @internal
     *
     * @param RouteMatch $routeMatch
     * @param Request    $request
     *
     * @return void
     */
    public function checkRequestMethod(RouteMatch $routeMatch, Request $request)
    {
        $controller = $routeMatch->getControllerReflection();
        $annotation = $this->getControllerAnnotation($controller, Allow::class);

        if ($annotation instanceof Allow) {
            $method = $request->getMethod();
            $allowedMethods = $annotation->getMethods();

            if (! in_array($method, $allowedMethods)) {
                throw new HttpMethodNotAllowedException($allowedMethods);
            }
        }
    }
}
