<?php

namespace Brick\Validation;

/**
 * Base class for validators.
 */
abstract class AbstractValidator implements Validator
{
    /**
     * The failure messages from the last validation.
     *
     * @var array
     */
    private $failureMessages = [];

    /**
     * {@inheritdoc}
     */
    final public function isValid($value)
    {
        $this->failureMessages = [];
        $this->validate($value);

        return ! $this->failureMessages;
    }

    /**
     * {@inheritdoc}
     */
    final public function getFailureMessages()
    {
        return $this->failureMessages;
    }

    /**
     * Adds a failure message.
     *
     * @param string $messageKey The message key.
     *
     * @return void
     *
     * @throws \RuntimeException If the message key is unknown.
     */
    final protected function addFailureMessage($messageKey)
    {
        $messages = $this->getPossibleMessages();

        if (! isset($messages[$messageKey])) {
            throw new \RuntimeException('Unknown message key: ' . $messageKey);
        }

        $this->failureMessages[$messageKey] = $messages[$messageKey];
    }

    /**
     * Validates the given value.
     *
     * The implementation must report failures by calling addFailureMessage().
     *
     * @param string $value The value to validate.
     *
     * @return void
     */
    abstract protected function validate($value);
}
