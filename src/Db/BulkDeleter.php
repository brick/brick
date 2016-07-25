<?php

namespace Brick\Db;

/**
 * Deletes rows from a database table in bulk.
 */
class BulkDeleter
{
    /**
     * The PDO connection.
     *
     * @var \PDO
     */
    private $pdo;

    /**
     * The name of the table to delete from.
     *
     * @var string
     */
    private $table;

    /**
     * The name of the fields used to uniquely identify rows.
     *
     * @var array
     */
    private $fields;

    /**
     * The number of fields above. This is to avoid redundant count() calls.
     *
     * @var int
     */
    private $numFields;

    /**
     * The number of records to delete per query.
     *
     * @var int
     */
    private $deletesPerQuery;

    /**
     * The number of delete queries to run per transaction, or zero to not use transactions.
     *
     * @var int
     */
    private $queriesPerTransaction;

    /**
     * The prepared statement to delete a group of records.
     *
     * @var \PDOStatement
     */
    private $deleteStatement;

    /**
     * A buffer containing the pending records to delete, to be grouped in a single query.
     *
     * @var array
     */
    private $buffer = [];

    /**
     * The number of records in the buffer.
     *
     * @var int
     */
    private $bufferSize = 0;

    /**
     * The number of delete queries executed in the current transaction.
     *
     * No transaction is running when this number is zero.
     *
     * @var int
     */
    private $queriesInTransaction = 0;

    /**
     * @param \PDO   $pdo                   The PDO connection.
     * @param string $table                 The name of the table to delete from.
     * @param array  $fields                The name of the fields used to uniquely identify rows.
     * @param int    $deletesPerQuery       The number of records to delete in a single query.
     * @param int    $queriesPerTransaction The number of delete queries to run in a single transaction,
     *                                      or zero to not use transactions. The default is to group all queries
     *                                      in a single transaction.
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(\PDO $pdo, string $table, array $fields, int $deletesPerQuery = 1000, int $queriesPerTransaction = PHP_INT_MAX)
    {
        $this->pdo       = $pdo;
        $this->table     = $table;
        $this->fields    = $fields;
        $this->numFields = count($fields);

        $this->deletesPerQuery       = $deletesPerQuery;
        $this->queriesPerTransaction = $queriesPerTransaction;

        if ($this->deletesPerQuery < 1) {
            throw new \InvalidArgumentException('The number of deletes per query must be 1 or more.');
        }

        if ($this->queriesPerTransaction < 0) {
            throw new \InvalidArgumentException('The number of queries per transaction must be 0 or more.');
        }

        $this->deleteStatement = $this->prepareDeleteStatement($deletesPerQuery);
    }

    /**
     * Queues a delete from the table.
     *
     * @param mixed ...$identity The identifier of the row to delete.
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function delete(...$identity)
    {
        $count = 0;

        foreach ($identity as $value) {
            $this->buffer[] = $value;
            $count++;
        }

        if ($count !== $this->numFields) {
            $this->buffer = array_slice($this->buffer, 0, - $count);

            throw new \InvalidArgumentException('The number of values does not match the identity field count.');
        }

        $this->bufferSize++;

        if ($this->bufferSize === $this->deletesPerQuery) {
            if ($this->queriesPerTransaction !== 0) {
                if ($this->queriesInTransaction === 0) {
                    $this->pdo->beginTransaction();
                }
            }

            $this->deleteStatement->execute($this->buffer);

            $this->buffer = [];
            $this->bufferSize = 0;

            if ($this->queriesPerTransaction !== 0) {
                $this->queriesInTransaction++;

                if ($this->queriesInTransaction === $this->queriesPerTransaction) {
                    $this->pdo->commit();
                    $this->queriesInTransaction = 0;
                }
            }
        }
    }

    /**
     * Flushes the pending deletes to the database and commits the current transaction.
     *
     * This is to be called once after the last delete() has been processed,
     * to force flushing the remaining queued deletes to the database table,
     * and commit the current transaction, if any.
     *
     * Do *not* forget to call this method after all the deletes have been buffered,
     * or it could result in data loss.
     *
     * @return void
     */
    public function flush()
    {
        if ($this->bufferSize !== 0) {
            if ($this->queriesPerTransaction !== 0) {
                if ($this->queriesInTransaction === 0) {
                    $this->pdo->beginTransaction();
                }
            }

            $statement = $this->prepareDeleteStatement($this->bufferSize);
            $statement->execute($this->buffer);

            $this->buffer = [];
            $this->bufferSize = 0;

            if ($this->queriesPerTransaction !== 0) {
                $this->queriesInTransaction++;
            }
        }

        if ($this->queriesPerTransaction !== 0) {
            if ($this->queriesInTransaction !== 0) {
                $this->pdo->commit();
                $this->queriesInTransaction = 0;
            }
        }
    }

    /**
     * @param int $numRecords The number of records to delete.
     *
     * @return \PDOStatement
     */
    private function prepareDeleteStatement($numRecords)
    {
        $parts = [];

        foreach ($this->fields as $field) {
            $parts[] = $field . ' = ?';
        }

        $where = '(' . implode(' AND ', $parts) . ')';

        $query = 'DELETE FROM ' . $this->table . ' WHERE ' . $where;
        $query .= str_repeat(' OR ' . $where, $numRecords - 1);

        return $this->pdo->prepare($query);
    }
}
