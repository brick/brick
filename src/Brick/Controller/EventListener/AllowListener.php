<?php

namespace Brick\Controller\EventListener;

use Brick\Event\Event;
use Brick\Event\AbstractEventListener;
use Brick\Application\Event\RouteMatchedEvent;
use Brick\Controller\Annotation\Allow;
use Brick\Controller\Annotation\Secure;
use Brick\Http\Exception\HttpRedirectException;
use Brick\Http\Request;
use Brick\Http\Exception\HttpMethodNotAllowedException;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;

/**
 * Configures the methods allowed on a controller with annotations.
 *
 * @todo rename to a more generic name as this now handles the Secure annotation as well
 */
class AllowListener extends AbstractEventListener
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
                if ($annotation instanceof Allow) {
                    $this->handleAllow($annotation, $event->getRequest());
                }

                if ($annotation instanceof Secure) {
                    $this->handleSecure($annotation, $event->getRequest());
                }
            }
        }
    }

    /**
     * @param Allow   $annotation
     * @param Request $request
     *
     * @return void
     *
     * @throws \Brick\Http\Exception\HttpMethodNotAllowedException
     */
    private function handleAllow(Allow $annotation, Request $request)
    {
        $method = $request->getMethod();
        $allowedMethods = $annotation->getMethods();

        if (! in_array($method, $allowedMethods)) {
            throw new HttpMethodNotAllowedException($allowedMethods);
        }
    }

    /**
     * @param Secure  $annotation
     * @param Request $request
     *
     * @return void
     *
     * @throws \Brick\Http\Exception\HttpRedirectException
     */
    private function handleSecure(Secure $annotation, Request $request)
    {
        if (! $request->isSecure()) {
            $url  = $request->getUrl();
            $url = str_replace('http:', 'https:', $url); // @todo not clean; Request could offer a way to do this?

            throw new HttpRedirectException($url, 301);
        }
    }
}
