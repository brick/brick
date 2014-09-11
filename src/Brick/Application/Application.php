<?php

namespace Brick\Application;

use Brick\Application\Event\ControllerInvocatedEvent;
use Brick\Application\Event\ControllerReadyEvent;
use Brick\Application\Event\ExceptionCaughtEvent;
use Brick\Application\Event\IncomingRequestEvent;
use Brick\Application\Event\NonResponseResultEvent;
use Brick\Application\Event\ResponseReceivedEvent;
use Brick\Application\Event\RouteMatchedEvent;
use Brick\Http\Request;
use Brick\Http\Response;
use Brick\Http\Server\RequestHandler;
use Brick\Http\Exception\HttpException;
use Brick\Http\Exception\HttpInternalServerErrorException;
use Brick\Routing\Route;
use Brick\Routing\Router;
use Brick\Event\EventDispatcher;
use Brick\Di\Injector;
use Brick\Di\InjectionPolicy;
use Brick\Di\ValueResolver;
use Brick\Di\Container;
use Brick\Di\ValueResolver\DefaultValueResolver;

/**
 * The web application kernel.
 */
class Application implements RequestHandler
{
    /**
     * @var \Brick\Di\Injector
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
     * @param Plugin $plugin The plugin to add.
     *
     * @return Application This instance, for chaining.
     */
    public function addPlugin(Plugin $plugin)
    {
        $plugin->register($this->eventDispatcher);

        return $this;
    }

    /**
     * Runs the application.
     *
     * @return void
     */
    public function run()
    {
        $request = Request::getCurrent();
        $response = $this->handle($request);
        $response->send();
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

        $event = new ExceptionCaughtEvent($exception, $request, $response);
        $this->eventDispatcher->dispatch(ExceptionCaughtEvent::class, $event);

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
     */
    private function handleRequest(Request $request)
    {
        $event = new IncomingRequestEvent($request);
        $this->eventDispatcher->dispatch(IncomingRequestEvent::class, $event);

        $match = $this->router->match($request);

        $event = new RouteMatchedEvent($request, $match);
        $this->eventDispatcher->dispatch(RouteMatchedEvent::class, $event);

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
            throw new \UnexpectedValueException('Unknown controller reflection type.');
        }

        $event = new ControllerReadyEvent($request, $match, $instance);
        $this->eventDispatcher->dispatch(ControllerReadyEvent::class, $event);

        $this->valueResolver->addParameters($event->getParameters());

        try {
            $response = $this->injector->invoke($callable);

            if (! $response instanceof Response) {
                throw $this->invalidResponse($response);
            }
        } catch (HttpException $e) {
            $response = $this->handleHttpException($e, $request);
        } finally {
            $event = new ControllerInvocatedEvent($request, $match, $instance);
            $this->eventDispatcher->dispatch(ControllerInvocatedEvent::class, $event);
        }

        $event = new ResponseReceivedEvent($request, $response, $match, $instance);
        $this->eventDispatcher->dispatch(ResponseReceivedEvent::class, $event);

        return $response;
    }

    /**
     * @param mixed $result
     *
     * @return \UnexpectedValueException
     */
    private function invalidResponse($result)
    {
        $message = 'Invalid response from controller: expected %s, got %s.';
        $type    = is_object($result) ? get_class($result) : gettype($result);

        return new \UnexpectedValueException(sprintf($message, Response::class, $type));
    }
}
