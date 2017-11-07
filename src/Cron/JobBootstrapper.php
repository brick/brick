<?php

namespace Brick\Cron;

/**
 * Instantiates a cron job.
 */
interface JobBootstrapper
{
    /**
     * @param string $className
     *
     * @return Job
     */
    public function bootstrap(string $className) : Job;
}
