<?php

namespace Brick\Controller\Plugin;

use Brick\Application\Event\RouteMatchEvent;
use Brick\Application\Events;
use Brick\Controller\Annotation\Secure;
use Brick\Event\EventDispatcher;
use Brick\Http\Exception\HttpRedirectException;

/**
 * Enforces the protocol allowed on a controller with annotations.
 *
 * If the Secure annotation is present on a controller class or method, HTTPS is enforced.
 * If the annotation is not present, both protocols are allowed by default.
 * If forceUnsecured() has been called, HTTP is enforced instead when the annotation is absent.
 *
 * The protocol is enforced with a 301 redirect.
 */
class SecurePlugin extends AbstractAnnotationPlugin
{
    /**
     * @var boolean
     */
    private $forceUnsecured = false;

    /**
     * @return SecurePlugin
     */
    public function forceUnsecured()
    {
        $this->forceUnsecured = true;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function register(EventDispatcher $dispatcher)
    {
        $dispatcher->addListener(Events::ROUTE_MATCHED, [$this, 'checkSecure']);
    }

    /**
     * @internal
     *
     * @param RouteMatchEvent $event
     *
     * @return void
     */
    public function checkSecure(RouteMatchEvent $event)
    {
        $controller = $event->getRouteMatch()->getControllerReflection();
        $request = $event->getRequest();

        $secure = $this->hasControllerAnnotation($controller, Secure::class);

        if ($secure !== $request->isSecure()) {
            if ($secure || $this->forceUnsecured) {
                $url = preg_replace_callback('/^https?/', function (array $matches) {
                    return $matches[0] == 'http' ? 'https' : 'http';
                }, $request->getUrl());

                throw new HttpRedirectException($url, 301);
            }
        }
    }
}
