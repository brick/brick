<?php

declare(strict_types=1);

namespace Brick\Doctrine;

use Doctrine\DBAL\Logging\SQLLogger;

/**
 * Doctrine logger with output to a file.
 */
class FileLogger implements SQLLogger
{
    /**
     * Log file pointer.
     *
     * @var resource
     */
    private $fp;

    /**
     * Query start time.
     *
     * @var float
     */
    private $startTime;

    /**
     * Total time spent on all queries.
     *
     * @var float
     */
    private $totalTime = 0.0;

    /**
     * @var int
     */
    private $queryNumber = 0;

    /**
     * @var bool
     */
    private $lock;

    /**
     * Class constructor.
     *
     * @param string $filename The log file name.
     * @param bool   $lock     Whether to use locks when writing to the file.
     */
    public function __construct(string $filename, bool $lock = false)
    {
        $this->fp = fopen($filename, 'ab');
        $this->lock = $lock;

        $this->write('SQL logger starting on ' . gmdate('r') . PHP_EOL . PHP_EOL);
    }

    /**
     * Class destructor.
     */
    public function __destruct()
    {
        fclose($this->fp);
    }

    /**
     * @param string $data
     *
     * @return void
     */
    private function write(string $data) : void
    {
        if ($this->lock) {
            flock($this->fp, LOCK_EX);
        }

        fwrite($this->fp, $data);

        if ($this->lock) {
            flock($this->fp, LOCK_UN);
        }
    }

    /**
     * @param mixed $var
     *
     * @return string
     */
    private function export($var) : string
    {
        if (is_array($var)) {
            $values = [];

            foreach ($var as $value) {
                $values[] = $this->export($value);
            }

            return '[' . implode(', ', $values) . ']';
        }

        if (is_object($var)) {
            if ($var instanceof \DateTime) {
                return $var->format(\DateTime::W3C);
            }

            if (method_exists($var, '__toString')) {
                return get_class($var) . '(' . $var . ')';
            }

            return get_class($var) . '@' . spl_object_hash($var);
        }

        return var_export($var, true);
    }

    /**
     * {@inheritdoc}
     */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        $this->queryNumber++;
        $this->startTime = microtime(true);

        $message = 'Query ' . $this->queryNumber . ': ' . $sql . PHP_EOL;

        if ($params !== null) {
            $message .= 'Parameters: ' . $this->export($params) . PHP_EOL;
        }

        if ($types !== null) {
            $message .= 'Types: ' . $this->export($types) . PHP_EOL;
        }

        $this->write($message);
    }

    /**
     * {@inheritdoc}
     */
    public function stopQuery()
    {
        $time = microtime(true) - $this->startTime;
        $this->totalTime += $time;

        $message = sprintf('This query: %.3f seconds; total: %.3f seconds.', $time, $this->totalTime);
        $this->write($message . PHP_EOL . PHP_EOL);
    }
}
