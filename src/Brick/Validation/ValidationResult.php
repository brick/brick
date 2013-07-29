<?php

namespace Brick\Validation;

/**
 * A validation result, containing zero or more failures.
 */
class ValidationResult
{
    /**
     * The validation failures. No failures means successful validation.
     *
     * @var ValidationFailure[]
     */
    private $failures = [];

    /**
     * @param string $messageKey        The message key.
     * @param array  $messageParameters The message parameters, if any.
     *
     * @return static This instance for chaining.
     */
    public function addFailure($messageKey, array $messageParameters = [])
    {
        $this->failures[] = new ValidationFailure($messageKey, $messageParameters);

        return $this;
    }

    /**
     * Returns whether this result has failures.
     *
     * @return bool
     */
    public function hasFailures()
    {
        return count($this->failures) != 0;
    }

    /**
     * Returns the validation failures.
     *
     * @return ValidationFailure[]
     */
    public function getFailures()
    {
        return $this->failures;
    }
}
