<?php

declare(strict_types=1);

namespace Brick\Cron;

/**
 * Interface that cron jobs must implement.
 */
interface Job
{
    /**
     * Returns how long the scheduler should wait between two runs of this job.
     *
     * @return int The time in seconds.
     */
    public function getTimeBetweenRunsInSeconds() : int;

    /**
     * Runs the job.
     *
     * It is the responsibility of the job to periodically check JobRunner::isInterrupted() and
     * return as soon as possible in a clean state whenever the runner has been interrupted.
     *
     * The value returned by this method dictates the behaviour of the scheduler:
     * * A non-zero value will trigger the job to be run again immediatly.
     * * A zero value will trigger a wait of getTimeBetweenRunsInSeconds() seconds before the next run.
     *
     * @param \Brick\Cron\JobRunner $runner The runner that manages this job.
     *
     * @return int The number of actions performed.
     */
    public function run(JobRunner $runner) : int;
}
