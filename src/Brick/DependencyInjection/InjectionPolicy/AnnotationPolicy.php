<?php

namespace Brick\DependencyInjection\InjectionPolicy;

use Brick\DependencyInjection\Annotation\Inject;
use Brick\DependencyInjection\InjectionPolicy;
use Brick\DependencyInjection\DependencyInjectionException;
use Brick\Reflection\ReflectionTools;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;

/**
 * Controls injection with annotations.
 */
class AnnotationPolicy implements InjectionPolicy
{
    /**
     * The annotation namespace.
     */
    const ANNOTATION_NAMESPACE = 'Brick\DependencyInjection\Annotation';

    /**
     * The annotation reader.
     *
     * @var \Doctrine\Common\Annotations\Reader
     */
    private $reader;

    /**
     * @var \Brick\Reflection\ReflectionTools
     */
    private $reflectionTools;

    /**
     * Class constructor.
     *
     * @param \Doctrine\Common\Annotations\Reader $reader
     */
    public function __construct(Reader $reader)
    {
        AnnotationRegistry::registerAutoloadNamespace(self::ANNOTATION_NAMESPACE);

        $this->reader = $reader;
        $this->reflectionTools = new ReflectionTools();
    }

    /**
     * {@inheritdoc}
     */
    public function isClassInjected(\ReflectionClass $class)
    {
        foreach ($this->reader->getClassAnnotations($class) as $annotation) {
            if ($annotation instanceof Inject) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isMethodInjected(\ReflectionMethod $method)
    {
        foreach ($this->reader->getMethodAnnotations($method) as $annotation) {
            if ($annotation instanceof Inject) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isPropertyInjected(\ReflectionProperty $property)
    {
        foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
            if ($annotation instanceof Inject) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameterKey(\ReflectionParameter $parameter)
    {
        $function = $parameter->getDeclaringFunction();

        if ($function instanceof \ReflectionMethod) {
            foreach ($this->reader->getMethodAnnotations($function) as $annotation) {
                if ($annotation instanceof Inject) {
                    $value = $annotation->getValue($parameter->getName());

                    if ($value !== null) {
                        return $value;
                    }
                }
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getPropertyKey(\ReflectionProperty $property)
    {
        foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
            if ($annotation instanceof Inject) {
                $value = $annotation->getSingleValue();

                if ($value !== null) {
                    return $value;
                }
            }
        }

        return null;
    }
}
