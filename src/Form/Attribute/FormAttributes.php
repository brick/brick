<?php

namespace Brick\Form\Attribute;

/**
 * Provides the form* attributes to inputs.
 */
trait FormAttributes
{
    use AbstractTag;

    /**
     * @param string $action
     *
     * @return static
     */
    public function setFormAction($action)
    {
        $this->getTag()->setAttribute('formaction', $action);

        return $this;
    }

    /**
     * @param string $enctype
     *
     * @return static
     */
    public function setFormEnctype($enctype)
    {
        $this->getTag()->setAttribute('formenctype', $enctype);

        return $this;
    }

    /**
     * @param string $method
     *
     * @return static
     */
    public function setFormMethod($method)
    {
        $this->getTag()->setAttribute('formmethod', $method);

        return $this;
    }

    /**
     * @param string $novalidate
     *
     * @return static
     */
    public function setFormNoValidate($novalidate)
    {
        $this->getTag()->setAttribute('formnovalidate', $novalidate);

        return $this;
    }

    /**
     * @param string $target
     *
     * @return static
     */
    public function setFormTarget($target)
    {
        $this->getTag()->setAttribute('formtarget', $target);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFormAction()
    {
        return $this->getTag()->getAttribute('formaction');
    }

    /**
     * @return string|null
     */
    public function getFormEnctype()
    {
        return $this->getTag()->getAttribute('formenctype');
    }

    /**
     * @return string|null
     */
    public function getFormMethod()
    {
        return $this->getTag()->getAttribute('formmethod');
    }

    /**
     * @return string|null
     */
    public function getFormNoValidate()
    {
        return $this->getTag()->getAttribute('formnovalidate');
    }

    /**
     * @return string|null
     */
    public function getFormTarget()
    {
        return $this->getTag()->getAttribute('formtarget');
    }
}
