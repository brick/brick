<?php

namespace Brick\Process;

/**
 * Exception thrown by the Process class.
 */
class ProcessException extends \RuntimeException
{
    /**
     * @param string $extension
     *
     * @return \Brick\Process\ProcessException
     */
    public static function missingExtension(string $extension) : ProcessException
    {
        return new self(sprintf('Missing required extension %s.', $extension));
    }

    /**
     * @param int    $errorCode
     * @param string $errorMessage
     *
     * @return \Brick\Process\ProcessException
     */
    public static function forkingError(int $errorCode, string $errorMessage) : ProcessException
    {
        return new self(sprintf(
            'Could not fork the current process: error code %d (%s).',
            $errorCode,
            $errorMessage
        ));
    }

    /**
     * @param int $expectedCode
     * @param int $actualCode
     *
     * @return \Brick\Process\ProcessException
     */
    public static function waitingError(int $expectedCode, int $actualCode) : ProcessException
    {
        return new self(sprintf(
            'Invalid return code received while waiting for process: expected %d, got %d.',
            $expectedCode,
            $actualCode
        ));
    }

    /**
     * @return \Brick\Process\ProcessException
     */
    public static function getPriorityError() : ProcessException
    {
        return new self('Could not get the process priority.');
    }

    /**
     * @return \Brick\Process\ProcessException
     */
    public static function setPriorityError() : ProcessException
    {
        return new self('Could not set the process priority.');
    }

    /**
     * @return \Brick\Process\ProcessException
     */
    public static function processStillRunning() : ProcessException
    {
        return new self('Cannot get exit information for a process that is still running.');
    }

    /**
     * @return \Brick\Process\ProcessException
     */
    public static function processExitedAbnormally() : ProcessException
    {
        return new self('Cannot get exit status code: the process exited abnormally.');
    }
}
