<?php

namespace Brick\Cron\Logger;

use Brick\Cron\Logger;

/**
 * Logger implementation that writes to a file.
 */
class FileLogger implements Logger
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @param string $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * {@inheritdoc}
     */
    public function log($message)
    {
        file_put_contents($this->filename, $message, FILE_APPEND | LOCK_EX);
    }
}
