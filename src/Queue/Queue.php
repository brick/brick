<?php

namespace Brick\Queue;

/**
 * Allows using a SQL table as a message queue, safe to use in a concurrent environment.
 */
class Queue
{
    /**
     * The SQLSTATE code for a deadlock.
     */
    const SQLSTATE_DEADLOCK = '40001';

    /**
     * The number of retries after a deadlock.
     */
    const DEADLOCK_RETRIES = 5;

    /**
     * The number of milliseconds to wait before retrying after a deadlock.
     */
    const DEADLOCK_RETRY_DELAY_MS = 5;

    /**
     * The alias for the message id column in the select query.
     */
    const ID_COLUMN_ALIAS = '__id';

    /**
     * The prepared statement to assign a message to the current process.
     *
     * @var \PDOStatement
     */
    private $assignMessageStatement;

    /**
     * The prepared statement to load a message freshly assigned.
     *
     * @var \PDOStatement
     */
    private $loadMessageStatement;

    /**
     * The prepared statement to remove a completed message.
     *
     * @var \PDOStatement
     */
    private $removeMessageStatement;

    /**
     * The prepared statement to un-assign all messages.
     *
     * @var \PDOStatement
     */
    private $unassignAllStatement;

    /**
     * The prepared statement to un-assign all messages currently assigned to a given process.
     *
     * @var \PDOStatement
     */
    private $unassignProcessStatement;

    /**
     * @param \PDO    $pdo       The PDO connection.
     * @param string  $table     The table name, optionally escaped.
     * @param string  $idColumn  The column name of the message id, optionally escaped.
     * @param string  $pidColumn The column name of the process id assigned to the message, optionally escaped.
     * @param array   $columns   The column names to return, optionally escaped. Defaults to all.
     *                           The array can be either a simple list of columns: ['a', 'b'],
     *                           or an associative array of alias to column name: ['aAlias' => 'a', 'bAlias' => 'b'].
     */
    public function __construct(\PDO $pdo, string $table, string $idColumn = 'id', string $pidColumn = 'pid', array $columns = ['*'])
    {
        $select = [];
        $columns[self::ID_COLUMN_ALIAS] = $idColumn;

        foreach ($columns as $alias => $column) {
            $select[] = is_int($alias) ? $column : ($column . ' AS ' . $alias);
        }

        $select = implode(', ', $select);

        $this->assignMessageStatement = $pdo->prepare(sprintf(
            'UPDATE %s SET %s = ? WHERE %s IS NULL ORDER BY %s ASC LIMIT 1',
            $table,
            $pidColumn,
            $pidColumn,
            $idColumn
        ));

        $this->loadMessageStatement = $pdo->prepare(sprintf(
            'SELECT %s FROM %s WHERE %s = ? ORDER BY %s DESC LIMIT 1',
            $select,
            $table,
            $pidColumn,
            $idColumn
        ));

        $this->removeMessageStatement = $pdo->prepare(sprintf(
            'DELETE FROM %s WHERE %s = ?',
            $table,
            $idColumn
        ));

        $this->unassignAllStatement = $pdo->prepare(sprintf(
            'UPDATE %s SET %s = NULL',
            $table,
            $pidColumn
        ));

        $this->unassignProcessStatement = $pdo->prepare(sprintf(
            'UPDATE %s SET %s = NULL WHERE %s = ?',
            $table,
            $pidColumn,
            $pidColumn
        ));
    }

    /**
     * Polls the queue for a message. The message gets assigned the current pid.
     *
     * @param int $pid The current process id.
     *
     * @return Message|null The assigned message, or null if the queue is empty.
     *
     * @throws \RuntimeException If an unexpected error occurs.
     */
    public function poll(int $pid) : ?Message
    {
        $this->executeStatement($this->assignMessageStatement, [$pid]);

        if ($this->assignMessageStatement->rowCount() == 0) {
            return null;
        }

        $this->executeStatement($this->loadMessageStatement, [$pid]);
        $data = $this->loadMessageStatement->fetch(\PDO::FETCH_ASSOC);
        $this->loadMessageStatement->closeCursor();

        if ($data === false) {
            throw new \RuntimeException('Could not find the message just assigned.');
        }

        $id = $data[self::ID_COLUMN_ALIAS];
        unset($data[self::ID_COLUMN_ALIAS]);

        return new Message($id, $pid, $data);
    }

    /**
     * Removes a finished message from the queue.
     *
     * @param Message $message The message to remove.
     *
     * @return bool Whether the message has been successfully removed.
     */
    public function remove(Message $message) : bool
    {
        $this->executeStatement($this->removeMessageStatement, [$message->getId()]);

        return $this->removeMessageStatement->rowCount() != 0;
    }

    /**
     * Un-assigns all currently assigned messages.
     *
     * This method should only be called when a scheduler starts,
     * assuming that a previous scheduler and all its workers have died.
     *
     * @return int The number of messages cleaned up.
     */
    public function unassignAll() : int
    {
        $this->executeStatement($this->unassignAllStatement, []);

        return $this->unassignAllStatement->rowCount();
    }

    /**
     * Un-assigns all messages currently assigned to the given process id.
     *
     * This method should only be called by a scheduler,
     * when a worker dies unexpectedly.
     *
     * @param int $pid The process id.
     *
     * @return int The number of messages cleaned up.
     */
    public function unassignProcess(int $pid) : int
    {
        $this->executeStatement($this->unassignProcessStatement, [$pid]);

        return $this->unassignAllStatement->rowCount();
    }

    /**
     * Executes a PDO statement, and automatically retries after a deadlock.
     *
     * @param \PDOStatement $statement  The PDO statement.
     * @param array         $parameters The bound parameters.
     *
     * @return void
     *
     * @throws \RuntimeException If the number of deadlock retries is exceeeded, or an error occurs.
     */
    private function executeStatement(\PDOStatement $statement, array $parameters) : void
    {
        for ($i = 0; $i < self::DEADLOCK_RETRIES; $i++) {
            if ($this->doExecuteStatement($statement, $parameters)) {
                return;
            }

            usleep(1000 * self::DEADLOCK_RETRY_DELAY_MS);
        }

        throw new \RuntimeException(sprintf(
            'Deadlock occurred %d times, aborting.',
            self::DEADLOCK_RETRIES
        ));
    }

    /**
     * Executes a PDO statement.
     *
     * This method handles all PDO::ATTR_ERRMODE configurations.
     *
     * @param \PDOStatement $statement  The PDO statement.
     * @param array         $parameters The bound parameters.
     *
     * @return bool Whether the statement executed successfully. False if deadlock.
     *
     * @throws \RuntimeException If any error other than a deadlock occurs.
     */
    private function doExecuteStatement(\PDOStatement $statement, array $parameters) : bool
    {
        try {
            if ($statement->execute($parameters)) {
                return true;
            }

            $errorInfo = $statement->errorInfo();
        }
        catch (\PDOException $e) {
            $errorInfo = $e->errorInfo;
        }

        list ($sqlstate, $driverErrorCode, $driverMessage) = $errorInfo;

        if ($sqlstate == self::SQLSTATE_DEADLOCK) {
            return false;
        }

        throw new \RuntimeException(sprintf(
            'A statement execution has failed with SQLSTATE %s (code %s): "%s".',
            $sqlstate,
            $driverErrorCode,
            $driverMessage
        ));
    }
}
