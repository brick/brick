<?php

namespace Brick\Cron;

/**
 * Interface that cron loggers must implement.
 *
 * The implementation must be aware that the instance of the logger will be used
 * in a process and its forks, and thus must not store any resource unless
 * it makes it safe to access the resource concurrently.
 */
interface Logger
{
    /**
     * @param string $message
     *
     * @return void
     */
    public function log(string $message) : void;
}
