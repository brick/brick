<?php

declare(strict_types=1);

namespace Brick\Cron;

/**
 * Runs and times a cron job.
 */
class JobRunner
{
    /**
     * @var \Brick\Cron\Job
     */
    private $job;

    /**
     * @var bool
     */
    private $interrupted = false;

    /**
     * @var int
     */
    private $numberOfRuns = 0;

    /**
     * @var int
     */
    private $lastStartTime = 0;

    /**
     * @var int
     */
    private $lastFinishTime = 0;

    /**
     * @var int
     */
    private $lastExecutionTimeInMs = 0;

    /**
     * @var int
     */
    private $totalExecutionTimeInMs = 0;

    /**
     * @param Job $job
     */
    public function __construct(Job $job)
    {
        $this->job = $job;
    }

    /**
     * @return void
     */
    public function setupSignalHandler() : void
    {
        // Ignore SIG_INT, that will be caught by the scheduler.
        pcntl_signal(SIGINT, SIG_IGN);

        pcntl_signal(SIGTERM, function() {
            $this->interrupted = true;
        });
    }

    /**
     * Returns whether a job interruption has been requested.
     *
     * @return bool
     */
    public function isInterrupted() : bool
    {
        return $this->interrupted;
    }

    /**
     * Returns the number of times the job has been run.
     *
     * @return int
     */
    public function getNumberOfRuns() : int
    {
        return $this->numberOfRuns;
    }

    /**
     * Returns the last time the job started, as a UNIX timestamp.
     *
     * Returns zero if the cron has not run yet.
     *
     * @return int
     */
    public function getLastStartTime() : int
    {
        return $this->lastStartTime;
    }

    /**
     * Returns the last time the job finished, as a UNIX timestamp.
     *
     * Returns zero if the cron has not run yet.
     *
     * @return int
     */
    public function getLastFinishTime() : int
    {
        return $this->lastFinishTime;
    }

    /**
     * Returns the last execution time, in milliseconds.
     *
     * Returns zero if the cron has not run yet.
     *
     * @return int
     */
    public function getLastExecutionTimeInMs() : int
    {
        return $this->lastExecutionTimeInMs;
    }

    /**
     * Returns the total execution time across all runs, in milliseconds.
     *
     * @return int
     */
    public function getTotalExecutionTimeInMs() : int
    {
        return $this->totalExecutionTimeInMs;
    }

    /**
     * Returns the average execution time across all runs, in milliseconds.
     *
     * Returns zero if the cron has not run yet.
     *
     * @return int
     */
    public function getAverageExecutionTimeInMs() : int
    {
        if ($this->numberOfRuns === 0) {
            return 0;
        }

        return (int) ($this->totalExecutionTimeInMs / $this->numberOfRuns);
    }

    /**
     * Runs the job and records the execution time.
     *
     * @return int The number of actions performed.
     */
    public function run() : int
    {
        $this->lastStartTime = time();
        $startTime = microtime(true);

        $actions = $this->job->run($this);

        $this->lastFinishTime = time();
        $finishTime = microtime(true);

        $ms = (int) (1000 * ($finishTime - $startTime));

        $this->numberOfRuns++;
        $this->lastExecutionTimeInMs = $ms;
        $this->totalExecutionTimeInMs += $ms;

        return $actions;
    }
}
