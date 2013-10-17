<?php

namespace Brick\Controller\EventListener;

use Brick\Event\AbstractEventListener;
use Brick\Reflection\ReflectionTools;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;

/**
 * Base class for event listeners checking controller annotations.
 */
abstract class AbstractAnnotationListener extends AbstractEventListener
{
    /**
     * @var \Doctrine\Common\Annotations\Reader
     */
    private $annotationReader;

    /**
     * @var \Brick\Reflection\ReflectionTools
     */
    private $reflectionTools;

    /**
     * @param Reader $annotationReader
     */
    public function __construct(Reader $annotationReader)
    {
        AnnotationRegistry::registerAutoloadNamespace('Brick\Controller\Annotation', __DIR__ . '/../../..');

        $this->annotationReader = $annotationReader;
        $this->reflectionTools  = new ReflectionTools();
    }

    /**
     * Returns an annotation on the class or method, if any.
     *
     * @todo annotation reading on generic functions (non-methods) once available in Doctrine
     *
     * @param \ReflectionFunctionAbstract $controller
     * @param string                      $annotationClass
     *
     * @return object|null The annotation, or null if not present.
     */
    protected function getControllerAnnotation(\ReflectionFunctionAbstract $controller, $annotationClass)
    {
        if ($controller instanceof \ReflectionMethod) {
            $annotations = $this->annotationReader->getMethodAnnotations($controller);
            foreach ($annotations as $annotation) {
                if ($annotation instanceof $annotationClass) {
                    return $annotation;
                }
            }

            $class = $controller->getDeclaringClass();
            $classes = $this->reflectionTools->getClassHierarchy($class);

            foreach ($classes as $class) {
                $annotations = $this->annotationReader->getClassAnnotations($class);
                foreach ($annotations as $annotation) {
                    if ($annotation instanceof $annotationClass) {
                        return $annotation;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Checks whether a controller has an annotation on the class or method.
     *
     * @param \ReflectionFunctionAbstract $controller
     * @param string                      $annotationClass
     *
     * @return boolean Whether the annotation is present.
     */
    protected function hasControllerAnnotation(\ReflectionFunctionAbstract $controller, $annotationClass)
    {
        return $this->getControllerAnnotation($controller, $annotationClass) !== null;
    }
}
