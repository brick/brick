<?php

namespace Brick\Cron\Logger;

use Brick\Cron\Logger;

/**
 * Logger implementation that chains zero or more loggers.
 * Can be used as a Null logger when used with zero loggers.
 */
class ChainLogger implements Logger
{
    /**
     * @var \Brick\Cron\Logger[]
     */
    private $loggers = [];

    /**
     * @param Logger $logger
     *
     * @return ChainLogger
     */
    public function addLogger(Logger $logger)
    {
        $this->loggers[] = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function log($message)
    {
        foreach ($this->loggers as $logger) {
            $logger->log($message);
        }
    }
}
