<?php

namespace Brick\Db;

/**
 * Inserts rows into a database table in bulk.
 */
class BulkInserter
{
    /**
     * The PDO connection.
     *
     * @var \PDO
     */
    private $pdo;

    /**
     * The name of the table to insert into.
     *
     * @var string
     */
    private $table;

    /**
     * The name of the fields to insert.
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
     * The number of records to insert per query.
     *
     * @var int
     */
    private $insertsPerQuery;

    /**
     * The number of insert queries to run per transaction, or zero to not use transactions.
     *
     * @var int
     */
    private $queriesPerTransaction;

    /**
     * The prepared statement to insert a group of records.
     *
     * @var \PDOStatement
     */
    private $insertStatement;

    /**
     * A buffer containing the pending records to insert, to be grouped in a single query.
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
     * The number of insert queries executed in the current transaction.
     *
     * No transaction is running when this number is zero.
     *
     * @var int
     */
    private $queriesInTransaction = 0;

    /**
     * @param \PDO   $pdo                   The PDO connection.
     * @param string $table                 The name of the table to insert into.
     * @param array  $fields                The name of the fields to insert.
     * @param int    $insertsPerQuery       The number of records to insert in a single query.
     * @param int    $queriesPerTransaction The number of insert queries to run in a single transaction,
     *                                      or zero to not use transactions. The default is to group all queries
     *                                      in a single transaction.
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(\PDO $pdo, $table, array $fields, $insertsPerQuery = 1000, $queriesPerTransaction = PHP_INT_MAX)
    {
        $this->pdo       = $pdo;
        $this->table     = $table;
        $this->fields    = $fields;
        $this->numFields = count($fields);

        $this->insertsPerQuery       = (int) $insertsPerQuery;
        $this->queriesPerTransaction = (int) $queriesPerTransaction;

        if ($this->insertsPerQuery < 1) {
            throw new \InvalidArgumentException('The number of inserts per query must be 1 or more.');
        }

        if ($this->queriesPerTransaction < 0) {
            throw new \InvalidArgumentException('The number of queries per transaction must be 0 or more.');
        }

        $this->insertStatement = $this->prepareInsertStatement($insertsPerQuery);
    }

    /**
     * Queues an insert into the table.
     *
     * @param array $data The values to insert.
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function insert(array $data)
    {
        $count = 0;

        foreach ($data as $value) {
            $this->buffer[] = $value;
            $count++;
        }

        if ($count !== $this->numFields) {
            $this->buffer = array_slice($this->buffer, 0, - $count);

            throw new \InvalidArgumentException('The number of values to insert does not match the field count.');
        }

        $this->bufferSize++;

        if ($this->bufferSize === $this->insertsPerQuery) {
            if ($this->queriesPerTransaction !== 0) {
                if ($this->queriesInTransaction === 0) {
                    $this->pdo->beginTransaction();
                }
            }

            $this->insertStatement->execute($this->buffer);

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
     * Flushes the pending data to the database and commits the current transaction.
     *
     * This is to be called once after the last insert() has been processed,
     * to force flushing the remaining queued inserts to the database table,
     * and commit the current transaction, if any.
     *
     * Do *not* forget to call this method after all the inserts have been buffered,
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

            $statement = $this->prepareInsertStatement($this->bufferSize);
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
     * @param int $numRecords The number of records to insert.
     *
     * @return \PDOStatement
     */
    private function prepareInsertStatement($numRecords)
    {
        $fields       = implode(', ', $this->fields);
        $placeholders = implode(', ', array_fill(0, $this->numFields, '?'));

        $query  = 'INSERT INTO ' . $this->table . ' (' . $fields . ') VALUES (' . $placeholders . ')';
        $query .= str_repeat(', (' . $placeholders . ')', $numRecords - 1);

        return $this->pdo->prepare($query);
    }
}
