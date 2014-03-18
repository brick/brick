<?php

namespace Brick\Form\Attribute;

use Brick\Validation\Validator\LengthValidator;

/**
 * Provides the maxlength attribute to inputs.
 */
trait MaxLengthAttribute
{
    use AbstractTag;

    /**
     * @param string $maxLength
     *
     * @return static
     */
    public function setMaxLength($maxLength)
    {
        $this->getTag()->setAttribute('maxlength', $maxLength);
        $this->removeValidators(LengthValidator::class);

        if ($maxLength !== '') {
            $validator = new LengthValidator();
            $this->addValidator($validator->setMaxLength($maxLength));
        }

        return $this;
    }
}
