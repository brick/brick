<?php

namespace Brick\IdentityResolver;

use Doctrine\ORM\EntityManager;

/**
 * The Doctrine implementation of the IdentityResolver.
 */
class DoctrineIdentityResolver implements IdentityResolver
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
    public function getIdentity($object)
    {
        $uow = $this->em->getUnitOfWork();

        if (! $uow->isInIdentityMap($object)) {
            throw IdentityResolutionException::cannotResolveIdentity($object, 'not in identity map');
        }

        $identity = $uow->getEntityIdentifier($object);

        if (count($identity) == 1) {
            foreach ($identity as $value) {
                return $value;
            }
        }

        return $identity;
    }
}
