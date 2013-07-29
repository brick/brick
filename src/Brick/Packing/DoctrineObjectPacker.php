<?php

namespace Brick\Packing;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Proxy\Proxy;

/**
 * The Doctrine implementation of the IdentityResolver.
 */
class DoctrineObjectPacker implements ObjectPacker
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Class constructor.
     *
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function pack($object)
    {
        $uow = $this->em->getUnitOfWork();

        if (! $uow->isInIdentityMap($object)) {
            return null;
        }

        $identity = $uow->getEntityIdentifier($object);

        if (count($identity) == 0) {
            return null;
        }

        return new ObjectSignature($this->getClass($object), $identity);
    }

    /**
     * {@inheritdoc}
     */
    public function unpack($object)
    {
        if ($object instanceof ObjectSignature) {
            return $this->em->getReference($object->getClass(), $object->getIdentity());
        }

        return null;
    }

    /**
     * @param object $entity
     * @return string
     */
    private function getClass($entity)
    {
        if ($entity instanceof Proxy) {
            return get_parent_class($entity);
        }

        return get_class($entity);
    }
}
