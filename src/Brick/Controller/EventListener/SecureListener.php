<?php

namespace Brick\Controller\EventListener;

use Brick\Event\Event;
use Brick\Event\AbstractEventListener;
use Brick\Application\Event\RouteMatchedEvent;
use Brick\Controller\Annotation\Secure;
use Brick\Http\Exception\HttpRedirectException;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;

/**
 * Configures the HTTP(S) protocol allowed on a controller with annotations.
 */
class SecureListener extends AbstractEventListener
{
    /**
     * @var \Doctrine\Common\Annotations\Reader
     */
    private $annotationReader;

    /**
     * @param Reader $annotationReader
     */
    public function __construct(Reader $annotationReader)
    {
        AnnotationRegistry::registerAutoloadNamespace('Brick\Controller\Annotation', __DIR__ . '/../../..');

        $this->annotationReader = $annotationReader;
    }

    /**
     * {@inheritdoc}
     */
    public function handleEvent(Event $event)
    {
        if ($event instanceof RouteMatchedEvent) {
            $controller = $event->getRouteMatch()->getControllerReflection();

            if ($controller instanceof \ReflectionMethod) {
                $annotations = $this->annotationReader->getMethodAnnotations($controller);
            } else {
                // @todo annotation reading on generic functions is not available yet
                $annotations = [];
            }

            foreach ($annotations as $annotation) {
                if ($annotation instanceof Secure) {
                    $request = $event->getRequest();
                    if (! $request->isSecure()) {
                        $url  = $request->getUrl();
                        $url = str_replace('http:', 'https:', $url); // @todo not clean; Request could offer a way to do this?

                        throw new HttpRedirectException($url, 301);
                    }
                }
            }
        }
    }
}
