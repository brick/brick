<?php

namespace Brick\Form;

use Brick\Filter\Filter;
use Brick\Validation\Validator;
use Brick\Validation\ValidationFailure;

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
    private $translator;

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

        $this->init();
        $this->setName($name);
    }

    /**
     * @param \Brick\Filter\Filter $filter
     *
     * @return static
     */
    public function addFilter(Filter $filter)
    {
        $this->filters[] = $filter;
        return $this;
    }

    /**
     * Adds a Validator to this Component.
     *
     * Adding twice the same instance of a validator has no effect.
     *
     * @param \Brick\Validation\Validator $validator
     *
     * @return static
     */
    public function addValidator(Validator $validator)
    {
        $hash = spl_object_hash($validator);
        $this->validators[$hash] = $validator;

        return $this;
    }

    /**
     * Checks whether the given Validator instance has been added to this Component.
     *
     * @param \Brick\Validation\Validator $validator
     *
     * @return boolean
     */
    public function hasValidator(Validator $validator)
    {
        $hash = spl_object_hash($validator);

        return isset($this->validators[$hash]);
    }

    /**
     * Removes a Validator instance from this Component.
     *
     * Removing a non-existent validator has no effect.
     *
     * @param \Brick\Validation\Validator $validator
     *
     * @return static
     */
    public function removeValidator(Validator $validator)
    {
        $hash = spl_object_hash($validator);
        unset($this->validators[$hash]);

        return $this;
    }

    /**
     * Validates the value upon form submission, and populates the component with this value.
     *
     * Validation errors will be accessible via hasErrors() and getErrors().
     *
     * @param string|array|null $value
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the given value is not of a correct type for this Component.
     */
    public function validate($value)
    {
        $this->checkCorrectType($value);
        $this->resetErrors();

        foreach ($this->filters as $filter) {
            $value = $filter->filter($value);
        }

        foreach ($this->validators as $validator) {
            foreach ($validator->validate($value)->getFailures() as $failure) {
                $this->addValidationFailure($failure);
            }
        }

        if ($value !== null) {
            $this->populate($value);
        }
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
     * @param \Brick\Validation\ValidationFailure $failure
     *
     * @return void
     */
    private function addValidationFailure(ValidationFailure $failure)
    {
        $this->addError($this->translator->translate($failure->getMessageKey()));
    }

    /**
     * Initializes the component.
     *
     * This method is to be called once, by the constructor only.
     * It can be overridden by individual components to initialize themselves.
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
     * @param string|array $value
     *
     * @return void
     */
    protected function populate($value)
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
