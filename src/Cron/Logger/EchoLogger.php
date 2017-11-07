<?php

declare(strict_types=1);

namespace Brick\Cron\Logger;

use Brick\Cron\Logger;

/**
 * Logger implementation that echoes to the terminal.
 */
class EchoLogger implements Logger
{
    /**
     * {@inheritdoc}
     */
    public function log(string $message) : void
    {
        echo $message;
    }
}
