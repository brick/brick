<?php

namespace Brick\ObjectConverter;

use Brick\ObjectConverter\Exception\ObjectNotConvertibleException;
use Brick\ObjectConverter\Exception\ObjectNotFoundException;

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
        if ($this->em->getMetadataFactory()->isTransient(get_class($object))) {
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
        if ($this->em->getMetadataFactory()->isTransient($className)) {
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
     * @return integer
     *
     * @throws \RuntimeException
     */
    private function getLockMode(array $options)
    {
        if (! isset($options['lock'])) {
            return LockMode::NONE;
        }

        switch ($options['lock']) {
            case 'READ':
                return LockMode::PESSIMISTIC_READ;

            case 'WRITE':
                return LockMode::PESSIMISTIC_WRITE;
        }

        throw new \RuntimeException('Invalid lock mode: ' . $options['lock']);
    }
}
