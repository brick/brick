<?php

namespace Brick\Validation\Validator;

use Brick\Validation\Validator;
use Brick\Validation\ValidationResult;

/**
 * Validates the length of a string.
 */
class LengthValidator implements Validator
{
    /**
     * @var integer
     */
    private $minLength;

    /**
     * @var integer
     */
    private $maxLength;

    /**
     * Class constructor.
     *
     * @param integer      $minLength
     * @param integer|null $maxLength
     */
    public function __construct($minLength, $maxLength = null)
    {
        $this->minLength = (int) $minLength;
        $this->maxLength = ($maxLength === null) ? $this->minLength : (int) $maxLength;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value)
    {
        $result = new ValidationResult();
        $length = strlen($value);

        if ($length < $this->minLength) {
            $result->addFailure('validator.length.min', [$this->minLength]);
        }
        elseif ($length > $this->maxLength) {
            $result->addFailure('validator.length.max', [$this->maxLength]);
        }

        return $result;
    }
}
