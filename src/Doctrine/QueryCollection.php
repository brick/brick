<?php

namespace Brick\Doctrine;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\CountWalker;

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
    private $query;

    /**
     * The elements in this collection, if it has been entirely loaded.
     *
     * @var array
     */
    private $elements = [];

    /**
     * Whether this collection has been entirely loaded or not.
     *
     * @var boolean
     */
    private $isLoaded = false;

    /**
     * Class constructor.
     *
     * @param \Doctrine\ORM\Query
     *
     * @throws \RuntimeException
     */
    public function __construct(Query $query)
    {
        if ($query->getFirstResult() !== null || $query->getMaxResults() !== null) {
            throw new \RuntimeException(
                'Cannot build a QueryCollection from a Query ' .
                'with firstResult or maxResults set.'
            );
        }

        $this->query = $this->cloneQuery($query);
    }

    /**
     * Returns the number of objects in the collection.
     *
     * {@inheritdoc}
     */
    public function count()
    {
        if ($this->isLoaded) {
            return count($this->elements);
        }

        return (int) $this->cloneQuery($this->query)
            ->setHint(Query::HINT_CUSTOM_TREE_WALKERS, [CountWalker::class])
            ->getSingleScalarResult();
    }

    /**
     * @return boolean
     */
    public function isEmpty()
    {
        return $this->count() === 0;
    }

    /**
     * Returns a subset of the collection as an array.
     *
     * @param integer      $offset The start offset, 0-based.
     * @param integer|null $length The maximum number of results, or null for no maximum.
     *
     * @return array<object>
     */
    public function slice($offset, $length = null)
    {
        if ($this->isLoaded) {
            return array_slice($this->elements, $offset, $length);
        }

        return $this->cloneQuery($this->query)
            ->setFirstResult($offset)
            ->setMaxResults($length)
            ->getResult();
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
    public function getIterator()
    {
        $this->load();

        return new \ArrayIterator($this->elements);
    }

    /**
     * Loads the whole collection and returns it as an array.
     *
     * @return array<object> The entities.
     */
    public function toArray()
    {
        $this->load();

        return $this->elements;
    }

    /**
     * Loads the collection into memory.
     *
     * @return void
     */
    private function load()
    {
        if (! $this->isLoaded) {
            $this->elements = $this->query->getResult();
            $this->isLoaded = true;
        }
    }

    /**
     * Clones a Query object.
     *
     * @param \Doctrine\ORM\Query $query The query to clone.
     *
     * @return \Doctrine\ORM\Query The cloned query.
     */
    private function cloneQuery(Query $query)
    {
        $queryClone = clone $query;
        $queryClone->setParameters($query->getParameters());

        foreach ($query->getHints() as $key => $value) {
            $queryClone->setHint($key, $value);
        }

        return $queryClone;
    }
}
