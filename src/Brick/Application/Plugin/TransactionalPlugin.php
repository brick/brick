<?php

namespace Brick\Application\Plugin;

use Brick\Application\Event\RouteMatchEvent;
use Brick\Application\Event\ControllerEvent;
use Brick\Application\Events;
use Brick\Application\Plugin;
use Brick\Application\Controller\Annotation\Transactional;
use Brick\Event\EventDispatcher;

use Doctrine\DBAL\Connection;
use Doctrine\Common\Annotations\Reader;

/**
 * Configures the start of a database transaction with annotations.
 */
class TransactionalPlugin extends AbstractAnnotationPlugin
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     * Class constructor.
     *
     * @param Connection $connection
     * @param Reader     $annotationReader
     */
    public function __construct(Connection $connection, Reader $annotationReader)
    {
        parent::__construct($annotationReader);

        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     */
    public function register(EventDispatcher $dispatcher)
    {
        $dispatcher->addListener(Events::ROUTE_MATCHED, [$this, 'beginTransaction']);
        $dispatcher->addListener(Events::CONTROLLER_INVOCATED, [$this, 'rollbackTransaction']);
    }

    /**
     * Starts a transaction before invocating the controller.
     *
     * @internal
     *
     * @param RouteMatchEvent $event
     *
     * @return void
     */
    public function beginTransaction(RouteMatchEvent $event)
    {
        $annotation = $this->getTransactionalAnnotation($event);

        if ($annotation) {
            $this->connection->setTransactionIsolation($annotation->getIsolationLevel());
            $this->connection->beginTransaction();
        }
    }

    /**
     * Rolls back a transaction that has not been committed.
     *
     * @internal
     *
     * @param ControllerEvent $event
     *
     * @return void
     */
    public function rollbackTransaction(ControllerEvent $event)
    {
        $annotation = $this->getTransactionalAnnotation($event);

        if ($annotation) {
            if ($this->connection->isTransactionActive()) {
                $this->connection->rollback();
            }
        }
    }

    /**
     * Returns the Transactional annotation for the controller.
     *
     * @param \Brick\Application\Event\RouteMatchEvent $event
     *
     * @return Transactional|null The annotation, or NULL if the controller is not transactional.
     *
     * @todo add support for annotations on functions when Doctrine will handle them
     */
    private function getTransactionalAnnotation(RouteMatchEvent $event)
    {
        $method = $event->getRouteMatch()->getControllerReflection();

        if ($method instanceof \ReflectionMethod) {
            $annotations = $this->annotationReader->getMethodAnnotations($method);

            foreach ($annotations as $annotation) {
                if ($annotation instanceof Transactional) {
                    return $annotation;
                }
            }
        }

        return null;
    }
}
