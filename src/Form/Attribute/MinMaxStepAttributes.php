<?php

namespace Brick\Form\Attribute;

/**
 * Provides the min, max and step attributes to inputs.
 */
trait MinMaxStepAttributes
{
    use AbstractTag;

    /**
     * @param string $min
     *
     * @return static
     */
    public function setMin($min)
    {
        $this->doSetMin($min);
        $this->getTag()->setAttribute('min', $min);

        return $this;
    }

    /**
     * @param string $max
     *
     * @return static
     */
    public function setMax($max)
    {
        $this->doSetMax($max);
        $this->getTag()->setAttribute('max', $max);

        return $this;
    }

    /**
     * @param string $step
     *
     * @return static
     */
    public function setStep($step)
    {
        $this->doSetStep($step);
        $this->getTag()->setAttribute('step', $step);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMin()
    {
        return $this->getTag()->getAttribute('min');
    }

    /**
     * @return string|null
     */
    public function getMax()
    {
        return $this->getTag()->getAttribute('max');
    }

    /**
     * @return string|null
     */
    public function getStep()
    {
        return $this->getTag()->getAttribute('step');
    }

    /**
     * Can be overridden by the class to perform additional actions when the min is set.
     *
     * @param string $min
     *
     * @return void
     */
    protected function doSetMin($min)
    {
    }

    /**
     * Can be overridden by the class to perform additional actions when the max is set.
     *
     * @param string $max
     *
     * @return void
     */
    protected function doSetMax($max)
    {
    }

    /**
     * Can be overridden by the class to perform additional actions when the step is set.
     *
     * @param string $step
     *
     * @return void
     */
    protected function doSetStep($step)
    {
    }
}
