<?php

namespace Brick\Cron;

/**
 * Interface that cron loggers must implement.
 */
interface Logger
{
    /**
     * @param string $message
     *
     * @return void
     */
    public function log($message);
}
