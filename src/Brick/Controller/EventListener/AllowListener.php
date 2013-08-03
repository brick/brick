<?php

namespace Brick\Controller\EventListener;

use Brick\Event\Event;
use Brick\Event\AbstractEventListener;
use Brick\Application\Event\RouteMatchedEvent;
use Brick\Http\Exception\HttpMethodNotAllowedException;
use Brick\Controller\Annotation\Allow;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;

/**
 * Configures the methods allowed on a controller with annotations.
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
                    $method = $event->getRequest()->getMethod();
                    $allowedMethods = $annotation->getMethods();

                    if (! in_array($method, $allowedMethods)) {
                        throw new HttpMethodNotAllowedException($allowedMethods);
                    }
                }
            }
        }
    }
}
