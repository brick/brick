<?php

namespace Brick\Form\Attribute;

use Brick\Validation\Validator\PatternValidator;

/**
 * Provides the pattern attribute to inputs.
 */
trait PatternAttribute
{
    use AbstractTag;

    /**
     * @param string $pattern
     *
     * @return static
     */
    public function setPattern($pattern)
    {
        $this->getTag()->setAttribute('pattern', $pattern);
        $this->removeValidators(PatternValidator::class);

        if ($pattern !== '') {
            $this->addValidator(new PatternValidator($pattern));
        }

        return $this;
    }

    /**
     * @return static
     */
    public function removePattern()
    {
        $this->getTag()->removeAttribute('pattern');
        $this->removeValidators(PatternValidator::class);

        return $this;
    }
}
