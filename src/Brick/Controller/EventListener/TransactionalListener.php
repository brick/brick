<?php

namespace Brick\Controller\EventListener;

use Brick\Event\Event;
use Brick\Event\AbstractEventListener;
use Brick\Application\Event\AbstractRouteMatchEvent;
use Brick\Application\Event\ControllerInvocatedEvent;
use Brick\Application\Event\RouteMatchedEvent;
use Brick\Controller\Annotation\Transactional;

use Doctrine\DBAL\Connection;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;

/**
 * Configures the start of a database transaction with annotations.
 */
class TransactionalListener extends AbstractEventListener
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
        AnnotationRegistry::registerAutoloadNamespace('Brick\Controller\Annotation');

        $this->connection       = $connection;
        $this->annotationReader = $annotationReader;
    }

    /**
     * {@inheritdoc}
     */
    public function handleEvent(Event $event)
    {
        /* Start a transaction before invocating the controller */
        if ($event instanceof RouteMatchedEvent) {
            $annotation = $this->getTransactionalAnnotation($event);
            if ($annotation) {
                $this->connection->setTransactionIsolation($annotation->getIsolationLevel());
                $this->connection->beginTransaction();
            }
        }

        /* Rollback a transaction that has not explicitly been committed */
        if ($event instanceof ControllerInvocatedEvent) {
            $annotation = $this->getTransactionalAnnotation($event);
            if ($annotation) {
                if ($this->connection->isTransactionActive()) {
                    $this->connection->rollback();
                }
            }
        }
    }

    /**
     * Returns the Transactional annotation for the controller.
     *
     * @param \Brick\Application\Event\AbstractRouteMatchEvent $event
     *
     * @return Transactional|null The annotation, or NULL if the controller is not transactional.
     *
     * @todo add support for annotations on functions when Doctrine will handle them
     */
    private function getTransactionalAnnotation(AbstractRouteMatchEvent $event)
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
