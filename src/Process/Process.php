<?php

declare(strict_types=1);

/**
 * Multiprocessing in PHP.
 *
 * Based on the work of Tudor Barbu:
 * @see http://blog.motane.lu/2009/01/02/multithreading-in-php/
 */

namespace Brick\Process;

/**
 * @todo move the extension checks to composer.json when released as a standalone component.
 */
class Process
{
    /**
     * The process id.
     *
     * @var int
     */
    private $pid;

    /**
     * The status information if the process has exited, or null if it is still running.
     *
     * @var int|null
     */
    private $status = null;

    /**
     * @var array
     */
    private static $requiredExtensions = ['pcntl', 'posix'];

    /**
     * @var bool
     */
    private static $extensionsChecked = false;

    /**
     * @param callable $callable
     * @param array    $arguments
     *
     * @throws \Brick\Process\ProcessException
     */
    public function __construct(callable $callable, array $arguments = [])
    {
        self::checkExtensions();

        $pid = @ pcntl_fork();

        if ($pid < 0) {
            $errorCode = pcntl_get_last_error();
            $errorMessage = pcntl_strerror($errorCode);

            throw ProcessException::forkingError($errorCode, $errorMessage);
        }

        if ($pid) {
            // Parent
            $this->pid = $pid;
        }
        else {
            // Child
            call_user_func_array($callable, $arguments);

            exit(0);
        }
    }

    /**
     * @return void
     *
     * @throws \Brick\Process\ProcessException
     */
    private static function checkExtensions() : void
    {
        if (! self::$extensionsChecked) {
            foreach (self::$requiredExtensions as $extension) {
                if (! extension_loaded($extension)) {
                    throw ProcessException::missingExtension($extension);
                }
            }

            self::$extensionsChecked = true;
        }
    }

    /**
     * Returns the PID of the process.
     *
     * @return int
     */
    public function getPid() : int
    {
        return $this->pid;
    }

    /**
     * Waits for this process to exit.
     *
     * @return void
     *
     * @throws ProcessException If an error occurs.
     */
    public function wait() : void
    {
        if ($this->status === null) {
            $pid = pcntl_waitpid($this->pid, $status);

            if ($pid !== $this->pid) {
                throw ProcessException::waitingError($this->pid, $pid);
            }

            $this->status = $status;
        }
    }

    /**
     * Returns whether the process is alive.
     *
     * @return bool
     *
     * @throws ProcessException If an error occurs while waiting for the process.
     */
    public function isAlive() : bool
    {
        if ($this->status === null) {
            $pid = pcntl_waitpid($this->pid, $status, WNOHANG);

            if ($pid === 0) {
                return true;
            }

            if ($pid !== $this->pid) {
                throw ProcessException::waitingError($this->pid, $pid);
            }

            $this->status = $status;
        }

        return false;
    }

    /**
     * Checks if the process has exited abnormally, i.e. has no exit status.
     *
     * This usually happens when the process has been killed.
     *
     * @return bool
     *
     * @throws ProcessException If the process is still running.
     */
    public function isNormalExit() : bool
    {
        if ($this->isAlive()) {
            throw ProcessException::processStillRunning();
        }

        return pcntl_wifexited($this->status);
    }

    /**
     * Returns the exit status of the process.
     *
     * This method will throw an exception if the process is still running,
     * or if the process has exited abnormally.
     *
     * You should check isAlive() and isNormalExit() before calling this method.
     *
     * @return int
     *
     * @throws ProcessException If the process is still running, or has exited abnormally.
     */
    public function getExitStatus() : int
    {
        if (! $this->isNormalExit()) {
            throw ProcessException::processExitedAbnormally();
        }

        return pcntl_wexitstatus($this->status);
    }

    /**
     * Sends a signal to the running process.
     *
     * @param int $signal One of the PCNTL signals constants.
     *
     * @return bool
     */
    public function signal(int $signal) : bool
    {
        return posix_kill($this->pid, $signal);
    }

    /**
     * @return int
     *
     * @throws ProcessException If an error occurs.
     */
    public function getPriority() : int
    {
        if (false === $priority = pcntl_getpriority($this->pid)) {
            throw ProcessException::getPriorityError();
        }

        return $priority;
    }

    /**
     * @param int $priority
     *
     * @return void
     *
     * @throws ProcessException If an error occurs.
     */
    public function setPriority(int $priority) : void
    {
        if (! pcntl_setpriority($priority, $this->pid)) {
            throw ProcessException::setPriorityError();
        }
    }
}
