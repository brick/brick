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
     * @var array
     */
    private $messageParameters;

    /**
     * Class constructor.
     *
     * @param string $messageKey
     * @param array  $messageParameters
     */
    public function __construct($messageKey, array $messageParameters)
    {
        $this->messageKey = $messageKey;
        $this->messageParameters = $messageParameters;
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

    /**
     * Returns an optional array of parameters to replace in the message.
     *
     * @return array
     */
    public function getMessageParameters()
    {
        return $this->messageParameters;
    }
}
