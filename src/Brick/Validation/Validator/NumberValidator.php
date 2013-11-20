<?php

namespace Brick\Validation\Validator;

use Brick\Validation\Validator;
use Brick\Validation\ValidationResult;

/**
 * Validates a number.
 *
 * @todo compare as strings of digits?
 * @todo handle decimal values?
 */
class NumberValidator implements Validator
{
    const REGEXP = '/^\-?[0-9]+$/';

    /**
     * @var integer|null
     */
    private $min = null;

    /**
     * @var integer|null
     */
    private $max = null;

    /**
     * @var integer|null
     */
    private $step = null;

    /**
     * {@inheritdoc}
     */
    public function validate($value)
    {
        if (! is_null($value) && ! is_string($value)) {
            throw new \InvalidArgumentException('Value must be a string or null');
        }

        $result = new ValidationResult();

        if (preg_match(self::REGEXP, $value) == 0) {
            $result->addFailure('validator.number.invalid');
        }
        elseif ($this->min !== null && $value < $this->min) {
            $result->addFailure('validator.number.min', [$this->min]);
        }
        elseif ($this->max !== null && $value > $this->max) {
            $result->addFailure('validator.number.max', [$this->max]);
        }
        elseif ($this->step !== null) {
            // @todo
        }

        return $result;
    }

    /**
     * @param integer $min
     *
     * @return static
     */
    public function setMin($min)
    {
        $this->min = (int) $min;

        return $this;
    }

    /**
     * @param integer $max
     *
     * @return static
     */
    public function setMax($max)
    {
        $this->max = (int) $max;

        return $this;
    }

    /**
     * @param integer $step
     *
     * @return static
     */
    public function setStep($step)
    {
        $this->step = (int) $step;

        return $this;
    }
}
