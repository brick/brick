<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Element\Input;
use Brick\Form\Attribute\AutocompleteAttribute;
use Brick\Form\Attribute\ListAttribute;
use Brick\Form\Attribute\MinMaxStepAttributes;
use Brick\Form\Attribute\PlaceholderAttribute;
use Brick\Form\Attribute\ReadOnlyAttribute;
use Brick\Form\Attribute\RequiredAttribute;
use Brick\Form\Attribute\ValueAttribute;
use Brick\Validation\Validator\NumberValidator;

/**
 * Represents a number input element.
 */
class Number extends Input
{
    use AutocompleteAttribute;
    use ListAttribute;
    use MinMaxStepAttributes;
    use PlaceholderAttribute;
    use ReadOnlyAttribute;
    use RequiredAttribute;
    use ValueAttribute;

    /**
     * @var NumberValidator
     */
    private $validator;

    /**
     * {@inheritdoc}
     */
    protected function init()
    {
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
     * {@inheritdoc}
     */
    protected function doSetMin($min)
    {
        $this->validator->setMin($min);
    }

    /**
     * {@inheritdoc}
     */
    protected function doSetMax($max)
    {
        $this->validator->setMax($max);
    }

    /**
     * {@inheritdoc}
     */
    public function doSetStep($step)
    {
        $this->validator->setStep($step === 'any' ? null : $step);
    }

    /**
     * {@inheritdoc}
     */
    protected function doPopulate($value)
    {
        $this->setValue($value);
    }
}
