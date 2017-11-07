<?php

namespace Brick\Doctrine;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

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
     * @var \Doctrine\ORM\Tools\Pagination\Paginator
     */
    private $paginator;

    /**
     * Class constructor.
     *
     * @param \Doctrine\ORM\Query
     */
    public function __construct(Query $query)
    {
        $this->paginator = new Paginator($query);
    }

    /**
     * Returns the number of objects in the collection.
     *
     * {@inheritdoc}
     */
    public function count() : int
    {
        return $this->paginator->count();
    }

    /**
     * @return bool
     */
    public function isEmpty() : bool
    {
        return $this->count() === 0;
    }

    /**
     * Returns a subset of the collection as an array.
     *
     * @param int $offset The start offset, 0-based.
     * @param int $length The maximum number of results.
     *
     * @return object[]
     */
    public function slice(int $offset, int $length) : array
    {
        $query = $this->paginator->getQuery();

        $query->setFirstResult($offset);
        $query->setMaxResults($length);

        return iterator_to_array($this->paginator);
    }

    /**
     * Returns the first entity in the collection, or null if the collection is empty.
     *
     * @return object|null The entity, or null if the collection is empty.
     */
    public function getFirstOrNull()
    {
        $objects = $this->slice(0, 1);

        return $objects ? $objects[0] : null;
    }

    /**
     * Required by interface IteratorAggregate.
     *
     * {@inheritdoc}
     */
    public function getIterator() : \Traversable
    {
        $query = $this->paginator->getQuery();

        $query->setFirstResult(null);
        $query->setMaxResults(null);

        return $this->paginator;
    }

    /**
     * Loads the whole collection and returns it as an array.
     *
     * @return object[] The entities.
     */
    public function toArray() : array
    {
        return iterator_to_array($this);
    }
}
