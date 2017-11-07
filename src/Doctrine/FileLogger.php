<?php

declare(strict_types=1);

namespace Brick\Doctrine;

use Doctrine\DBAL\Logging\SQLLogger;

/**
 * Doctrine logger with output to a file.
 */
class FileLogger implements SQLLogger
{
    const APPEND = 0;
    const OVERWRITE = 1;

    /**
     * Log file pointer.
     *
     * @var resource
     */
    protected $fp;

    /**
     * Query start time.
     *
     * @var float
     */
    protected $startTime;

    /**
     * Total time spent on all queries.
     *
     * @var float
     */
    protected $totalTime = 0.0;

    /**
     * Class constructor.
     *
     * @param string $filename
     * @param int    $writeMode
     */
    public function __construct(string $filename, int $writeMode)
    {
        $mode = ($writeMode === self::OVERWRITE) ? 'wt' : 'at';
        $this->fp = fopen($filename, $mode);

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
    protected function write(string $data) : void
    {
        fwrite($this->fp, $data);
    }

    /**
     * @param array|null $array
     *
     * @return string
     */
    protected function format(array $array = null) : string
    {
        if ($array === null) {
            return 'none';
        }

        foreach ($array as & $item) {
            if (is_object($item)) {
                if ($item instanceof \DateTime) {
                    $item = $item->format(\DateTime::W3C);
                } elseif (method_exists($item, '__toString')) {
                    $item = get_class($item) . '(' . $item . ')';
                } else {
                    $item = get_class($item) . '@' . spl_object_hash($item);
                }
            }
        }

        return var_export($array, true);
    }

    /**
     * {@inheritdoc}
     */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        $this->startTime = microtime(true);

        $this->write($sql . PHP_EOL);
        $this->write('Parameters: ' . $this->format($params) . PHP_EOL);
        $this->write('Types: ' . $this->format($types) . PHP_EOL);
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
