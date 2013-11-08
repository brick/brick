<?php

namespace Brick\Validation;

/**
 * A validation failure.
 */
class ValidationFailure
{
    /**
     * @var string
     */
    private $messageKey;

    /**
     * Class constructor.
     *
     * @param string $messageKey
     */
    public function __construct($messageKey)
    {
        $this->messageKey = $messageKey;
    }

    /**
     * Returns the key of the message, to be used with a translator.
     *
     * @return string
     */
    public function getMessageKey()
    {
        return $this->messageKey;
    }
}
