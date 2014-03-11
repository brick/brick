<?php

namespace Brick\Validation\Validator;

use Brick\Validation\AbstractValidator;
use Brick\Math\Decimal;

/**
 * Validates a number.
 */
class NumberValidator extends AbstractValidator
{
    /**
     * @var \Brick\Math\Decimal|null
     */
    private $min = null;

    /**
     * @var \Brick\Math\Decimal|null
     */
    private $max = null;

    /**
     * @var \Brick\Math\Decimal|null
     */
    private $step = null;

    /**
     * {@inheritdoc}
     */
    public function getPossibleMessages()
    {
        return [
            'validator.number.invalid' => 'The number is not valid.',
            'validator.number.min'     => 'The number is too small.',
            'validator.number.max'     => 'The number is too large.',
            'validator.number.step'    => 'The number is not an acceptable value.',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function validate($value)
    {
        try {
            $value = Decimal::of($value);
        }
        catch (\InvalidArgumentException $e) {
            $this->addFailureMessage('validator.number.invalid');
        }

        if ($this->min && $value->isLessThan($this->min)) {
            $this->addFailureMessage('validator.number.min');
        } elseif ($this->max && $value->isGreaterThan($this->max)) {
            $this->addFailureMessage('validator.number.max');
        } elseif ($this->step && ! $value->mod($this->step)->isZero()) {
            $this->addFailureMessage('validator.number.step');
        }
    }

    /**
     * @param number|string|null $min The minimum value, or null to remove it.
     *
     * @return static
     *
     * @throws \InvalidArgumentException If not a valid number.
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
     * @throws \InvalidArgumentException If not a valid number.
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
     * @throws \InvalidArgumentException If the step is not a valid number or not positive.
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
