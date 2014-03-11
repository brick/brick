<?php

namespace Brick\Form;

use Brick\Filter\Filter;
use Brick\Validation\Validator;

/**
 * Base class for Element and Group. Aggregated by Form.
 */
abstract class Component extends Base
{
    /**
     * The owning form.
     *
     * @var \Brick\Form\Form
     */
    protected $form;

    /**
     * @var \Brick\Translation\Translator
     */
    protected $translator;

    /**
     * @var boolean
     */
    protected $required = false;

    /**
     * @var \Brick\Filter\Filter[]
     */
    private $filters = [];

    /**
     * @var \Brick\Validation\Validator[]
     */
    private $validators = [];

    /**
     * Class constructor.
     *
     * @param Form   $form
     * @param string $name
     */
    public function __construct(Form $form, $name)
    {
        $this->form = $form;

        // @todo
        $this->translator = new \Brick\Translation\Translator(new \Brick\Translation\Loader\NullLoader());
        $this->translator->setLocale(\Brick\Locale\Locale::create('en'));

        $this->setName($name);
        $this->init();
    }

    /**
     * Populates the component with the given value.
     *
     * The value will be filtered and validated.
     * Validation errors will be accessible via hasErrors() and getErrors().
     *
     * @param string|array|null $value
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the given value is not of a correct type for this Component.
     */
    public function populate($value)
    {
        $this->checkCorrectType($value);
        $this->resetErrors();

        $empty = ($value === '' || $value === [] || $value === null);

        if ($empty && $this->required) {
            $this->addTranslatedError('form-required');
        }

        if (! $empty && is_string($value)) {
            $value = $this->filter($value);
            $this->validate($value);
        }

        if ($value === null) {
            $value = $this->isArray() ? [] : '';
        }

        $this->doPopulate($value);
    }

    /**
     * @param boolean $required
     *
     * @return static
     */
    public function setRequired($required)
    {
        $this->required = (bool) $required;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * Returns whether the given value is of the correct type for this Component.
     *
     * @param mixed $value
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the given value is not of a correct type for this Component.
     */
    private function checkCorrectType($value)
    {
        if ($value !== null) {
            $isCorrectType = $this->isArray() ? is_array($value) : is_string($value);
            $expectedType = $this->isArray() ? 'array' : 'string';
            $actualType = gettype($value);

            if (! $isCorrectType) {
                $message = 'Invalid value received for "%s": expected %s, got %s';
                $message = sprintf($message, $this->getName(), $expectedType, $actualType);

                throw new \InvalidArgumentException($message);
            }
        }
    }

    /**
     * @param string $message
     *
     * @return void
     */
    private function addTranslatedError($message)
    {
        $this->addError($this->translator->translate($message));
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private function filter($value)
    {
        foreach ($this->filters as $filter) {
            $value = $filter->filter($value);
        }

        return $value;
    }

    /**
     * @param string $value
     *
     * @return void
     */
    private function validate($value)
    {
        foreach ($this->validators as $validator) {
            foreach ($validator->validate($value)->getFailures() as $failure) {
                $this->addTranslatedError($failure->getMessageKey());
            }
        }
    }

    /**
     * Adds a filter.
     *
     * Adding twice the same instance of a filter has no effect.
     *
     * @param \Brick\Filter\Filter $filter
     *
     * @return static
     */
    protected function addFilter(Filter $filter)
    {
        $hash = spl_object_hash($filter);
        $this->filters[$hash] = $filter;

        return $this;
    }

    /**
     * Checks whether a filter is present.
     *
     * @param \Brick\Filter\Filter $filter
     *
     * @return boolean
     */
    protected function hasFilter(Filter $filter)
    {
        $hash = spl_object_hash($filter);

        return isset($this->filters[$hash]);
    }

    /**
     * Removes a filter.
     *
     * Removing a non-existent filter has no effect.
     *
     * @param \Brick\Filter\Filter $filter
     *
     * @return static
     */
    protected function removeFilter(Filter $filter)
    {
        $hash = spl_object_hash($filter);
        unset($this->filters[$hash]);

        return $this;
    }

    /**
     * Removes all filters of the given class name.
     *
     * @param string $className
     *
     * @return static
     */
    protected function removeFilters($className)
    {
        foreach ($this->filters as $key => $filter) {
            if ($filter instanceof $className) {
                unset($this->filters[$key]);
            }
        }

        return $this;
    }

    /**
     * Adds a validator.
     *
     * Adding twice the same instance of a validator has no effect.
     *
     * @param \Brick\Validation\Validator $validator
     *
     * @return static
     */
    protected function addValidator(Validator $validator)
    {
        $hash = spl_object_hash($validator);
        $this->validators[$hash] = $validator;

        return $this;
    }

    /**
     * Checks whether a validator is present.
     *
     * @param \Brick\Validation\Validator $validator
     *
     * @return boolean
     */
    protected function hasValidator(Validator $validator)
    {
        $hash = spl_object_hash($validator);

        return isset($this->validators[$hash]);
    }

    /**
     * Removes a validator.
     *
     * Removing a non-existent validator has no effect.
     *
     * @param \Brick\Validation\Validator $validator
     *
     * @return static
     */
    protected function removeValidator(Validator $validator)
    {
        $hash = spl_object_hash($validator);
        unset($this->validators[$hash]);

        return $this;
    }

    /**
     * Removes all validators of the given class name.
     *
     * @param string $className
     *
     * @return static
     */
    protected function removeValidators($className)
    {
        foreach ($this->validators as $key => $validator) {
            if ($validator instanceof $className) {
                unset($this->validators[$key]);
            }
        }

        return $this;
    }

    /**
     * Initializes the component.
     *
     * This method is called at the end of the constructor.
     *
     * @return void
     */
    protected function init()
    {
    }

    /**
     * Populates the component with the value(s) received from the form submission.
     *
     * This method does nothing for elements whose state don't change upon submission
     * (buttons, file inputs, ...) but is overridden by elements whose
     * state does change upon submission (text inputs, checkboxes, selects, ...)
     *
     * @param string|array|null $value
     *
     * @return void
     */
    protected function doPopulate($value)
    {
    }

    /**
     * Returns whether the Component expects an array as input data.
     *
     * This method is to be overridden only by components which can return an array.
     *
     * @return bool True if the component expects an array, false if it expects a string.
     */
    protected function isArray()
    {
        return false;
    }

    /**
     * Sets the name of the component, that will be posted in the form data.
     *
     * @param string $name
     *
     * @return void
     */
    abstract protected function setName($name);

    /**
     * Returns the name of the component, that will be posted in the form data.
     *
     * @return string
     */
    abstract public function getName();
}
