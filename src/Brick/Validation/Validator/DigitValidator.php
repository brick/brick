<?php

namespace Brick\Validation\Validator;

use Brick\Validation\Validator;
use Brick\Validation\ValidationResult;

/**
 * Validates a string of digits.
 */
class DigitValidator implements Validator
{
    /**
     * @var integer|null
     */
    private $minLength = null;

    /**
     * @var integer|null
     */
    private $maxLength = null;

    /**
     * {@inheritdoc}
     */
    public function validate($value)
    {
        $result = new ValidationResult();

        if (! ctype_digit($value)) {
            $result->addFailure('validator.digit.invalid');
        }
        elseif ($this->minLength !== null && strlen($value) < $this->minLength) {
            $result->addFailure('validator.digit.min-length', [$this->minLength]);
        }
        elseif ($this->maxLength !== null && strlen($value) > $this->maxLength) {
            $result->addFailure('validator.digit.max-length', [$this->maxLength]);
        }

        return $result;
    }

    /**
     * @param integer $minLength
     *
     * @return static
     */
    public function setMinLength($minLength)
    {
        $this->minLength = (int) $minLength;

        return $this;
    }

    /**
     * @param integer $maxLength
     *
     * @return static
     */
    public function setMaxLength($maxLength)
    {
        $this->maxLength = (int) $maxLength;

        return $this;
    }
}
