<?php

namespace Brick\Doctrine;

use Doctrine\ORM\Query;

/**
 * This helper collection wraps a non-executed Doctrine Query.
 *
 * It can transparently be counted, iterated and sliced,
 * performing as little entity hydration as possible.
 */
class QueryCollection implements \Countable, \IteratorAggregate
{
    /**
     * The Doctrine Query object.
     *
     * @var \Doctrine\ORM\Query
     */
    protected $query;

    /**
     * The elements in this collection, if it has been entirely loaded.
     *
     * @var array
     */
    protected $elements = [];

    /**
     * Whether this collection has been entirely loaded or not.
     *
     * @var boolean
     */
    protected $isLoaded = false;

    /**
     * Class constructor.
     *
     * @param \Doctrine\ORM\Query
     * @throws \RuntimeException
     */
    public function __construct(Query $query)
    {
        if ($query->getFirstResult() !== null || $query->getMaxResults() !== null) {
            throw new \RuntimeException('Cannot build a QueryCollection from a Query with firstResult or maxResults set');
        }

        $this->query = $this->cloneQuery($query);
    }

    /**
     * Clones a Query object.
     *
     * @param  \Doctrine\ORM\Query $query
     * @return \Doctrine\ORM\Query
     */
    protected function cloneQuery(Query $query)
    {
        $queryClone = clone $query;
        $queryClone->setParameters($query->getParameters());

        return $queryClone;
    }

    /**
     * Returns the number of objects in the collection.
     *
     * {@inheritdoc}
     */
    public function count()
    {
        if ($this->isLoaded) {
            // if the collection is entirely loaded, return the count of the internal array
            return count($this->elements);
        }

        $query = $this->cloneQuery($this->query);

        $query->setHint(Query::HINT_CUSTOM_TREE_WALKERS, ['DoctrineExtensions\Paginate\CountWalker']);
        $query->setFirstResult(null)->setMaxResults(null);

        return $query->getSingleScalarResult();
    }

    /**
     * Returns a subset of the collection as an array.
     *
     * @param  integer      $offset
     * @param  integer|null $length
     * @return object[]
     */
    public function slice($offset, $length = null)
    {
        if ($this->isLoaded) {
            // if the collection is entirely loaded, get the slice from the internal array
            return array_slice($this->elements, $offset, $length, true);
        }

        $query = $this->cloneQuery($this->query);

        $query->setFirstResult($offset);
        $query->setMaxResults($length);

        return $query->getResult();
    }

    /**
     * @return object|null
     */
    public function getFirstOrNull()
    {
        $objects = $this->slice(0, 1);

        return count($objects) ? reset($objects) : null;
    }

    /**
     * Required by interface IteratorAggregate.
     *
     * {@inheritdoc}
     */
    public function getIterator()
    {
        if (! $this->isLoaded) {
            $this->elements = $this->query->getResult();
            $this->isLoaded = true;
        }

        return new \ArrayIterator($this->elements);
    }

    /**
     * Loads the whole collection and returns it as an array.
     *
     * @return array An array of objects.
     */
    public function toArray()
    {
        if (! $this->isLoaded) {
            $this->elements = $this->query->getResult();
            $this->isLoaded = true;
        }

        return $this->elements;
    }
}
