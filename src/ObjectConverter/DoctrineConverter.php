<?php

namespace Brick\ObjectConverter;

use Brick\ObjectConverter\Exception\ObjectNotConvertibleException;
use Brick\ObjectConverter\Exception\ObjectNotFoundException;

use Doctrine\Common\Persistence\Proxy;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\LockMode;

/**
 * Converts objects to identities back and forth.
 */
class DoctrineConverter implements ObjectConverter
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function shrink($object)
    {
        if ($this->isTransient($object)) {
            return null;
        }

        $uow = $this->em->getUnitOfWork();

        if (! $uow->isInIdentityMap($object)) {
            throw new ObjectNotConvertibleException();
        }

        $identity = $uow->getEntityIdentifier($object);

        if (count($identity) == 1) {
            foreach ($identity as $value) {
                return $value;
            }
        }

        return $identity;
    }

    /**
     * {@inheritdoc}
     */
    public function expand($className, $value, array $options = [])
    {
        if ($this->isTransient($className)) {
            return null;
        }

        $entity = $this->em->find($className, $value, $this->getLockMode($options));

        if ($entity) {
            return $entity;
        }

        throw new ObjectNotFoundException();
    }

    /**
     * @param array $options
     *
     * @return integer|null
     *
     * @throws \RuntimeException
     */
    private function getLockMode(array $options)
    {
        if (! isset($options['lock'])) {
            return null;
        }

        switch ($options['lock']) {
            case 'READ':
                return LockMode::PESSIMISTIC_READ;

            case 'WRITE':
                return LockMode::PESSIMISTIC_WRITE;
        }

        throw new \RuntimeException('Invalid lock mode: ' . $options['lock']);
    }

    /**
     * @param string|object $class
     *
     * @return boolean
     */
    private function isTransient($class)
    {
        if (is_object($class)) {
            $class = ($class instanceof Proxy)
                ? get_parent_class($class)
                : get_class($class);
        }

        return $this->em->getMetadataFactory()->isTransient($class);
    }
}
