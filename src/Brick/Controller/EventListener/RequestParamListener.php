<?php

namespace Brick\Controller\EventListener;

use Brick\Controller\ParameterConverter\ParameterConverter;
use Brick\Controller\ParameterConverter\NullConverter;
use Brick\Controller\Annotation\RequestParam;
use Brick\Application\Event\ControllerReadyEvent;
use Brick\Event\Event;
use Brick\Event\AbstractEventListener;
use Brick\Http\Request;
use Brick\Http\Exception\HttpBadRequestException;
use Brick\Http\Exception\HttpInternalServerErrorException;
use Brick\Reflection\ReflectionTools;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;

/**
 * Configures injection of request parameters into the controller with annotations.
 */
class RequestParamListener extends AbstractEventListener
{
    /**
     * @var \Doctrine\Common\Annotations\Reader
     */
    private $annotationReader;

    /**
     * @var \Brick\Controller\ParameterConverter\ParameterConverter
     */
    private $parameterConverter;

    /**
     * @var \Brick\Reflection\ReflectionTools
     */
    private $reflectionTools;

    /**
     * @param Reader                  $annotationReader
     * @param ParameterConverter|null $converter
     */
    public function __construct(Reader $annotationReader, ParameterConverter $converter = null)
    {
        AnnotationRegistry::registerAutoloadNamespace('Brick\Controller\Annotation', __DIR__ . '/../../..');

        $this->annotationReader   = $annotationReader;
        $this->parameterConverter = $converter ?: new NullConverter();
        $this->reflectionTools    = new ReflectionTools();
    }

    /**
     * {@inheritdoc}
     */
    public function handleEvent(Event $event)
    {
        if ($event instanceof ControllerReadyEvent) {
            $event->setParameters($this->getParameters(
                $event->getRequest(),
                $event->getRouteMatch()->getControllerReflection()
            ));
        }
    }

    /**
     * @param \Brick\Http\Request         $request
     * @param \ReflectionFunctionAbstract $controller
     *
     * @return array
     */
    private function getParameters(Request $request, \ReflectionFunctionAbstract $controller)
    {
        if ($controller instanceof \ReflectionMethod) {
            $annotations = $this->annotationReader->getMethodAnnotations($controller);
        } else {
            // @todo annotation reading on generic functions is not available yet
            $annotations = [];
        }

        $parameters = [];
        foreach ($controller->getParameters() as $parameter) {
            $parameters[$parameter->getName()] = $parameter;
        }

        $result = [];

        foreach ($annotations as $annotation) {
            if ($annotation instanceof RequestParam) {
                $result[$annotation->getBindTo()] = $this->getParameter(
                    $annotation,
                    $controller,
                    $parameters,
                    $request
                );
            }
        }

        return $result;
    }

    /**
     * @param RequestParam                $annotation The annotation.
     * @param \ReflectionFunctionAbstract $controller The reflection of the controller function.
     * @param \ReflectionParameter[]      $parameters An array of ReflectionParameter for the function, indexed by name.
     * @param Request                     $request    The HTTP Request.
     *
     * @return mixed The value to assign to the function parameter.
     *
     * @throws \RuntimeException
     * @throws \Brick\Http\Exception\HttpBadRequestException
     * @throws \Brick\Http\Exception\HttpNotFoundException
     * @throws \Brick\Http\Exception\HttpInternalServerErrorException
     */
    private function getParameter(RequestParam $annotation, \ReflectionFunctionAbstract $controller, array $parameters, Request $request)
    {
        $requestParameters = $annotation->getRequestParameters($request);
        $parameterName = $annotation->getName();
        $bindTo = $annotation->getBindTo();

        if (! isset($parameters[$bindTo])) {
            throw $this->unknownParameterException($controller, $annotation);
        }

        $parameter = $parameters[$bindTo];

        if (! isset($requestParameters[$parameterName])) {
            if ($parameter->isDefaultValueAvailable()) {
                return $parameter->getDefaultValue();
            }

            throw $this->missingParameterException($controller, $annotation);
        }

        $value = $requestParameters[$parameterName];

        if ($parameter->isArray() && ! is_array($value)) {
            throw $this->invalidArrayParameterException($controller, $annotation);
        }

        return $this->parameterConverter->convertParameter($parameter, $value);
    }

    /**
     * @param \ReflectionFunctionAbstract $controller
     * @param \Brick\Controller\Annotation\RequestParam $annotation
     *
     * @return \Brick\Http\Exception\HttpInternalServerErrorException
     */
    private function unknownParameterException(\ReflectionFunctionAbstract $controller, RequestParam $annotation)
    {
        return new HttpInternalServerErrorException(sprintf(
            '%s() does not have a $%s parameter, please check your annotation.',
            $this->reflectionTools->getFunctionName($controller),
            $annotation->getBindTo()
        ));
    }

    /**
     * @param \ReflectionFunctionAbstract               $controller
     * @param \Brick\Controller\Annotation\RequestParam $annotation
     *
     * @return \Brick\Http\Exception\HttpBadRequestException
     */
    private function missingParameterException(\ReflectionFunctionAbstract $controller, RequestParam $annotation)
    {
        return new HttpBadRequestException(sprintf(
            '%s() requires a %s parameter "%s" which is missing in the request.',
            $this->reflectionTools->getFunctionName($controller),
            $annotation->getParameterType(),
            $annotation->getName()
        ));
    }

    /**
     * @param \ReflectionFunctionAbstract               $controller
     * @param \Brick\Controller\Annotation\RequestParam $annotation
     *
     * @return \Brick\Http\Exception\HttpBadRequestException
     */
    private function invalidArrayParameterException(\ReflectionFunctionAbstract $controller, RequestParam $annotation)
    {
        return new HttpBadRequestException(sprintf(
            '%s() expects an array for %s parameter "%s" (bound to $%s), string given.',
            $this->reflectionTools->getFunctionName($controller),
            $annotation->getParameterType(),
            $annotation->getName(),
            $annotation->getBindTo()
        ));
    }
}
