<?php

namespace Brick\Tests\Db;

/**
 * Mocks a PDO connection for unit testing.
 */
class PDOMock extends \PDO
{
    /**
     * @var integer
     */
    private $statementNumber = 0;

    /**
     * @var array
     */
    private $log = [];

    /**
     * Empty constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return boolean
     */
    public function beginTransaction()
    {
        $this->log('START TRANSACTION');

        return true;
    }

    /**
     * @return boolean
     */
    public function commit()
    {
        $this->log('COMMIT');

        return true;
    }

    /**
     * @param string     $statement
     * @param array|null $options
     *
     * @return PDOStatementMock
     */
    public function prepare($statement, $options = null)
    {
        $this->log('PREPARE ' . $statement);

        return new PDOStatementMock($this, ++$this->statementNumber);
    }

    /**
     * @param string $info
     */
    public function log($info)
    {
        $this->log[] = $info;
    }

    /**
     * @return array
     */
    public function getLog()
    {
        return $this->log;
    }
}
