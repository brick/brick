<?php

declare(strict_types=1);

namespace Brick\Cron\Logger;

use Brick\Cron\Logger;

/**
 * Logger implementation that chains zero or more loggers.
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
    public function addLogger(Logger $logger) : ChainLogger
    {
        $this->loggers[] = $logger;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function log(string $message) : void
    {
        foreach ($this->loggers as $logger) {
            $logger->log($message);
        }
    }
}
