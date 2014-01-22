<?php

namespace Brick\Validation\Validator;

use Brick\Validation\Validator;
use Brick\Validation\ValidationResult;
use Brick\Math\Decimal;

/**
 * Validates a number.
 */
class NumberValidator implements Validator
{
    /**
     * @var Decimal|null
     */
    private $min = null;

    /**
     * @var Decimal|null
     */
    private $max = null;

    /**
     * @var Decimal|null
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

        try {
            $value = Decimal::of($value);

            if ($this->min && $value->isLessThan($this->min)) {
                $result->addFailure('validator.number.min', [$this->min]);
            }
            elseif ($this->max && $value->isGreaterThan($this->max)) {
                $result->addFailure('validator.number.max', [$this->max]);
            }
            elseif ($this->step && ! $value->mod($this->step)->isZero()) {
                $result->addFailure('validator.number.step', [$this->step]);
            }
        }
        catch (\InvalidArgumentException $e) {
            $result->addFailure('validator.number.invalid');
        }

        return $result;
    }

    /**
     * @param number|string|null $min The minimum value, or null to remove it.
     *
     * @return static
     *
     * @throws \InvalidArgumentException
     */
    public function setMin($min)
    {
        if ($min !== null) {
            $min = Decimal::of($min);
        }

        $this->min = $min;

        return $this;
    }

    /**
     * @param number|string|null $max The maximum value, or null to remove it.
     *
     * @return static
     *
     * @throws \InvalidArgumentException
     */
    public function setMax($max)
    {
        if ($max !== null) {
            $max = Decimal::of($max);
        }

        $this->max = $max;

        return $this;
    }

    /**
     * @param number|string|null $step The step, or null to remove it.
     *
     * @return static
     *
     * @throws \InvalidArgumentException
     */
    public function setStep($step)
    {
        if ($step !== null) {
            $step = Decimal::of($step);

            if ($step->isNegativeOrZero()) {
                throw new \InvalidArgumentException('The number validator step must be strictly positive.');
            }
        }

        $this->step = $step;

        return $this;
    }
}
