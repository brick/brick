<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Element\Input;
use Brick\Validation\Validator\NumberValidator;

/**
 * Represents a number input element.
 */
class Number extends Text
{
    /**
     * @var NumberValidator
     */
    private $validator;

    /**
     * {@inheritdoc}
     */
    protected function init()
    {
        parent::init();

        $this->validator = new NumberValidator();
        $this->validator->setStep(1);

        $this->addValidator($this->validator);
    }

    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'number';
    }

    /**
     * Sets the minimum value. Default is no minimum.
     *
     * @param number|string $min The minimum value.
     *
     * @return static
     */
    public function setMin($min)
    {
        $this->validator->setMin($min);

        return $this->setAttribute('min', $min);
    }

    /**
     * Sets the maximum value. Default is no maximum.
     *
     * @param number|string $max The maximum value.
     *
     * @return static
     */
    public function setMax($max)
    {
        $this->validator->setMax($max);

        return $this->setAttribute('max', $max);
    }

    /**
     * Sets the validation step. Default is 1.
     *
     * @param number|string $step The step, or "any" to allow any value.
     *
     * @return static
     */
    public function setStep($step)
    {
        $this->validator->setStep($step == 'any' ? null : $step);

        return $this->setAttribute('step', $step);
    }
}
