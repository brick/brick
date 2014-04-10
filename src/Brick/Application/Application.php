<?php

namespace Brick\Application;

use Brick\Http\Request;
use Brick\Http\Response;
use Brick\Http\Server\RequestHandler;
use Brick\Http\Exception\HttpException;
use Brick\Http\Exception\HttpInternalServerErrorException;
use Brick\Routing\Route;
use Brick\Routing\Router;
use Brick\Event\EventListener;
use Brick\Event\EventDispatcher;
use Brick\DependencyInjection\Injector;
use Brick\DependencyInjection\InjectionPolicy;
use Brick\DependencyInjection\ValueResolver;
use Brick\DependencyInjection\Container;
use Brick\DependencyInjection\ValueResolver\DefaultValueResolver;

/**
 * The web application kernel.
 */
class Application implements RequestHandler
{
    /**
     * @var \Brick\DependencyInjection\Injector
     */
    private $injector;

    /**
     * @var \Brick\Application\ControllerValueResolver
     */
    private $valueResolver;

    /**
     * @var \Brick\Event\EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var \Brick\Routing\Router
     */
    private $router;

    /**
     * Class constructor.
     *
     * @param ValueResolver   $resolver
     * @param InjectionPolicy $policy
     */
    public function __construct(ValueResolver $resolver, InjectionPolicy $policy)
    {
        $this->valueResolver   = new ControllerValueResolver($resolver);
        $this->injector        = new Injector($this->valueResolver, $policy);
        $this->eventDispatcher = new EventDispatcher();
        $this->router          = new Router();
    }

    /**
     * Creates a simple application.
     *
     * @return Application
     */
    public static function create()
    {
        return new Application(
            new DefaultValueResolver(),
            new InjectionPolicy\NullPolicy()
        );
    }

    /**
     * Creates an application using the given dependency injection container.
     *
     * @param Container $container
     *
     * @return Application
     */
    public static function createWithContainer(Container $container)
    {
        return new Application(
            $container->getValueResolver(),
            $container->getInjectionPolicy()
        );
    }

    /**
     * @param Route $route
     *
     * @return Application
     */
    public function addRoute(Route $route)
    {
        $this->router->addRoute($route);

        return $this;
    }

    /**
     * @param EventListener $listener
     *
     * @return Application
     */
    public function addEventListener(EventListener $listener)
    {
        $this->eventDispatcher->addListener($listener);

        return $this;
    }

    /**
     * @param \Brick\Http\Request $request
     * @return \Brick\Http\Response
     */
    public function handle(Request $request)
    {
        try {
            return $this->handleRequest($request);
        } catch (HttpException $e) {
            return $this->handleHttpException($e, $request);
        } catch (\Exception $e) {
            return $this->handleUncaughtException($e, $request);
        }
    }

    /**
     * Converts an HttpException to a Response.
     *
     * @param \Brick\Http\Exception\HttpException $exception
     * @param \Brick\Http\Request                 $request
     *
     * @return \Brick\Http\Response
     */
    private function handleHttpException(HttpException $exception, Request $request)
    {
        $response = new Response();

        $response->setContent($exception);
        $response->setStatusCode($exception->getStatusCode());
        $response->setHeaders($exception->getHeaders());
        $response->setHeader('Content-Type', 'text/plain');

        $event = new Event\ExceptionCaughtEvent($exception, $request, $response);
        $this->eventDispatcher->dispatch($event);

        return $response;
    }

    /**
     * Wraps an uncaught exception in an HttpInternalServerErrorException, and converts it to a Response.
     *
     * @param \Exception          $exception
     * @param \Brick\Http\Request $request
     *
     * @return \Brick\Http\Response
     */
    private function handleUncaughtException(\Exception $exception, Request $request)
    {
        $httpException = new HttpInternalServerErrorException('Uncaught exception', $exception);

        return $this->handleHttpException($httpException, $request);
    }

    /**
     * @param \Brick\Http\Request $request The request to handle.
     *
     * @return \Brick\Http\Response The generated response.
     *
     * @throws \Brick\Http\Exception\HttpNotFoundException If no route matches the request.
     * @throws \UnexpectedValueException                   If a route or controller returned an unexpected value.
     * @throws \Exception                                  Catches and rethrows any exception.
     */
    private function handleRequest(Request $request)
    {
        $event = new Event\IncomingRequestEvent($request);
        $this->eventDispatcher->dispatch($event);

        $match = $this->router->match($request);

        $event = new Event\RouteMatchedEvent($request, $match);
        $this->eventDispatcher->dispatch($event);

        $controllerReflection = $match->getControllerReflection();
        $instance = null;

        $this->valueResolver->setRequest($request);
        $this->valueResolver->setParameters($match->getParameters());

        if ($controllerReflection instanceof \ReflectionMethod) {
            $className = $controllerReflection->getDeclaringClass()->getName();
            $instance = $this->injector->instantiate($className);

            $callable = [$instance, $controllerReflection->getName()];
        } elseif ($controllerReflection instanceof \ReflectionFunction) {
            $callable = $controllerReflection->getClosure();
        } else {
            throw new \UnexpectedValueException('Invalid controller reflection type.');
        }

        $event = new Event\ControllerReadyEvent($request, $match, $instance);
        $this->eventDispatcher->dispatch($event);

        $this->valueResolver->addParameters($event->getParameters());

        try {
            $response = $this->injector->invoke($callable);
            $this->checkResponse($response);
        } catch (HttpException $e) {
            $response = $this->handleHttpException($e, $request);
        } finally {
            $event = new Event\ControllerInvocatedEvent($request, $match, $instance);
            $this->eventDispatcher->dispatch($event);
        }

        $event = new Event\ResponseReceivedEvent($request, $response, $match, $instance);
        $this->eventDispatcher->dispatch($event);

        return $response;
    }

    /**
     * Ensures that the return value of a controller is a Response object.
     *
     * @param mixed $response
     *
     * @return void
     *
     * @throws \UnexpectedValueException
     */
    private function checkResponse($response)
    {
        if (! $response instanceof Response) {
            throw new \UnexpectedValueException(sprintf(
                'Invalid response from controller: expected %s, got %s.',
                Response::class,
                is_object($response) ? get_class($response) : gettype($response)
            ));
        }
    }
}
