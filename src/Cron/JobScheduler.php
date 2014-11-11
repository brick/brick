<?php

namespace Brick\Cron;

use Brick\Process\Process;

/**
 * Schedules the cron jobs.
 */
class JobScheduler
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var JobBootstrapper
     */
    private $bootstrapper;

    /**
     * @var bool
     */
    private $interrupted = false;

    /**
     * @var string[]
     */
    private $jobClasses = [];

    /**
     * @var \Brick\Process\Process[]
     */
    private $processes = [];

    /**
     * The time to wait between the start of two processes, to avoid a load spike, in milliseconds.
     *
     * @var int
     */
    private $timeBetweenColdStartInMs = 1000;

    /**
     * The time to wait between polling of processes to check they're still alive, in milliseconds.
     *
     * @var int
     */
    private $pollTimeInMs = 100;

    /**
     * Class constructor.
     *
     * @param Logger      $logger
     * @param JobBootstrapper $bootstrapper
     */
    public function __construct(Logger $logger, JobBootstrapper $bootstrapper)
    {
        $this->logger = $logger;
        $this->bootstrapper = $bootstrapper;

        foreach ([SIGINT, SIGTERM] as $signal) {
            pcntl_signal($signal, function () {
                $this->log('Interrupted, stopping processes.');
                $this->interrupted = true;
            });
        }
    }

    /**
     * @param string $className
     *
     * @return JobScheduler
     */
    public function addJobClass($className)
    {
        $this->jobClasses[] = $className;

        return $this;
    }

    /**
     * @return void
     */
    public function run()
    {
        foreach ($this->jobClasses as $jobClass) {
            if ($this->interrupted) {
                $this->log('Aborting startup.');
                break;
            }

            $this->launchProcess($jobClass);
            $this->sleepMs($this->timeBetweenColdStartInMs);
        }

        while (! $this->interrupted) {
            foreach ($this->processes as $class => $process) {
                if (! $process->isAlive()) {
                    $this->logJob($class, 'Process has exited unexpectedly.');
                    $this->launchProcess($class);
                }
            }

            $this->sleepMs($this->pollTimeInMs);
        }

        foreach ($this->processes as $process) {
            $process->signal(SIGTERM);
        }

        foreach ($this->processes as $process) {
            $process->wait();
        }
    }

    /**
     * @param string $className
     *
     * @return void
     */
    private function launchProcess($className)
    {
        $this->logJob($className, 'Starting process.');

        $this->processes[$className] = new Process(function() use ($className) {
            $job = $this->bootstrapper->bootstrap($className);
            $jobRunner = new JobRunner($job);
            $jobRunner->setupSignalHandler();

            while (! $jobRunner->isInterrupted()) {
                $this->logJob($className, 'Running job.');

                $actions = $jobRunner->run();

                $this->logJob($className, sprintf('Finished in %d ms, %d actions performed.', $jobRunner->getLastExecutionTimeInMs(), $actions));

                if ($actions == 0) {
                    $this->logJob($className, sprintf('Next run in %d s.', $job->getTimeBetweenRunsInSeconds()));
                    sleep($job->getTimeBetweenRunsInSeconds());
                }
            }

            $this->logJob($className, 'Successfully exited.');
        });

        $this->logJob($className, sprintf('Process started as PID %d.', $this->processes[$className]->getPid()));
    }

    /**
     * @param string $message
     *
     * @return void
     */
    private function log($message)
    {
        preg_match('/(\.[0-9]{6})[0-9]* ([0-9]+)/', microtime(), $matches);

        $this->logger->log(sprintf('%s %s' . PHP_EOL, $matches[2] . $matches[1], $message));
    }

    /**
     * @param string $jobClassName
     * @param string $message
     *
     * @return void
     */
    private function logJob($jobClassName, $message)
    {
        $this->log(sprintf('%s: %s', $jobClassName, $message));
    }

    /**
     * Sleeps for the given number of milliseconds.
     *
     * @param int $ms
     *
     * @return void
     */
    private function sleepMs($ms)
    {
        usleep(1000 * $ms);
    }
}
