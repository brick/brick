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
     * @param integer $min
     *
     * @return static
     */
    public function setMin($min)
    {
        $this->validator->setMin($min);

        return $this->setAttribute('min', $min);
    }

    /**
     * @param integer $max
     *
     * @return static
     */
    public function setMax($max)
    {
        $this->validator->setMax($max);

        return $this->setAttribute('max', $max);
    }

    /**
     * @param integer $step
     *
     * @return static
     */
    public function setStep($step)
    {
        $this->validator->setStep($step);

        return $this->setAttribute('step', $step);
    }
}
