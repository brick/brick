<?php

declare(strict_types=1);

namespace Brick\Doctrine\Types;

/**
 * Exception thrown when a value does not match the expected type.
 */
class UnexpectedValueException extends \UnexpectedValueException
{
    /**
     * Class constructor.
     *
     * @param string $expectedType The expected type.
     * @param mixed  $actualValue  The actual value.
     */
    public function __construct(string $expectedType, $actualValue)
    {
        $type = is_object($actualValue) ? get_class($actualValue) : gettype($actualValue);
        $message = sprintf('Expected %s, got %s.', $expectedType, $type);

        parent::__construct($message);
    }
}
