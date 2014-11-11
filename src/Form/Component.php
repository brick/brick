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
     * @var \Brick\Translation\Translator|null
     */
    protected $translator = null;

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
     * @throws \InvalidArgumentException If the given value is not of a correct type for this component.
     */
    public function populate($value)
    {
        $isArray = $this->isArray();

        if ($value === null) {
            $value = $isArray ? [] : '';
        } else {
            $isCorrectType = $isArray ? is_array($value) : is_string($value);

            if (! $isCorrectType) {
                throw new \InvalidArgumentException(sprintf(
                    'Invalid value received for "%s": expected %s, got %s',
                    $this->getName(),
                    $isArray ? 'array' : 'string',
                    gettype($value)
                ));
            }
        }

        $this->resetErrors();

        $empty = ($value === '' || $value === []);

        if ($empty && $this->required) {
            $this->addTranslatableError('form.required', 'This field is required.');
        }

        if (! $empty && ! $isArray) {
            $value = $this->filter($value);
            $this->validate($value);
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
     * @param string $messageKey
     * @param string $message
     *
     * @return void
     */
    private function addTranslatableError($messageKey, $message)
    {
        if ($this->translator) {
            $translatedMessage = $this->translator->translate($messageKey);
            if ($translatedMessage !== $messageKey) {
                $message = $translatedMessage;
            }
        }

        $this->addError($message);
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
            if (! $validator->isValid($value)) {
                foreach ($validator->getFailureMessages() as $messageKey => $message) {
                    $this->addTranslatableError($messageKey, $message);
                }
            }
        }
    }

    /**
     * @todo protected
     *
     * Adds a filter.
     *
     * Adding twice the same instance of a filter has no effect.
     *
     * @param \Brick\Filter\Filter $filter
     *
     * @return static
     */
    public function addFilter(Filter $filter)
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
     * @todo protected
     *
     * Adds a validator.
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
     * Not all components care with a value, reset buttons for example will not implement this method.
     *
     * @param string|array $value
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
