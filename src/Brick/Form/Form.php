<?php

namespace Brick\Form;

use Brick\Html\ContainerTag;
use Brick\Http\Request;

/**
 * Represents an HTML form.
 */
class Form extends Base
{
    const ENCTYPE_URLENCODED = 'application/x-www-form-urlencoded';
    const ENCTYPE_MULTIPART  = 'multipart/form-data';
    const ENCTYPE_PLAIN      = 'text/plain';

    /**
     * @var \Brick\Html\ContainerTag
     */
    private $tag;

    /**
     * @var \Brick\Form\Component[]
     */
    private $components = [];

    /**
     * @var array
     */
    private $ids = [];

    /**
     * @param Element $element
     *
     * @return string
     */
    public function generateElementId(Element $element)
    {
        preg_match('/^([a-zA-Z0-9]*)/', $element->getName(), $matches);
        $name = $matches[0];

        if (! isset($this->ids[$name])) {
            $this->ids[$name] = 0;
        }

        return $this->getId() . '-' . $name . '-' . $this->ids[$name]++;
    }

    /**
     * @return string
     */
    public function getId()
    {
        if (! $this->tag->hasAttribute('id')) {
            $this->tag->setAttribute('id', $this->generateUid());
        }

        return $this->tag->getAttribute('id');
    }

    /**
     * @return string
     */
    private function generateUid()
    {
        preg_match('/^0\.([0-9]+) ([0-9]+)$/', microtime(), $matches);

        return 'form-' . $matches[2] . '-' . $matches[1];
    }

    /**
     * @return \Brick\Html\ContainerTag
     */
    private function getTag()
    {
        if ($this->tag === null) {
            $this->tag = new ContainerTag('form');
        }

        return $this->tag;
    }

    /**
     * @param string    $name
     * @param Component $component
     *
     * @return Component
     *
     * @throws \RuntimeException
     */
    private function addComponent($name, Component $component)
    {
        if (isset($this->components[$name])) {
            throw new \RuntimeException(sprintf('Duplicate component name "%"', $name));
        }

        $this->components[$name] = $component;

        return $component;
    }

    /**
     * @return \Brick\Form\Component[]
     */
    public function getComponents()
    {
        return $this->components;
    }

    /**
     * @param string $name
     *
     * @return \Brick\Form\Component
     *
     * @throws \RuntimeException
     */
    public function getComponent($name)
    {
        if (isset($this->components[$name])) {
            return $this->components[$name];
        }

        throw new \RuntimeException(sprintf('No component named "%s"', $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Button\Button
     */
    public function addButtonButton($name)
    {
        return $this->addComponent($name, new Element\Button\Button($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Button\Reset
     */
    public function addButtonReset($name)
    {
        return $this->addComponent($name, new Element\Button\Reset($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Button\Submit
     */
    public function addButtonSubmit($name)
    {
        return $this->addComponent($name, new Element\Button\Submit($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Input\Button
     */
    public function addInputButton($name)
    {
        return $this->addComponent($name, new Element\Input\Button($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Input\Checkbox
     */
    public function addInputCheckbox($name)
    {
        return $this->addComponent($name, new Element\Input\Checkbox($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Input\Color
     */
    public function addInputColor($name)
    {
        return $this->addComponent($name, new Element\Input\Color($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Input\Date
     */
    public function addInputDate($name)
    {
        return $this->addComponent($name, new Element\Input\Date($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Input\DateTime
     */
    public function addInputDateTime($name)
    {
        return $this->addComponent($name, new Element\Input\DateTime($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Input\DateTimeLocal
     */
    public function addInputDateTimeLocal($name)
    {
        return $this->addComponent($name, new Element\Input\DateTimeLocal($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Input\Email
     */
    public function addInputEmail($name)
    {
        return $this->addComponent($name, new Element\Input\Email($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Input\File
     */
    public function addInputFile($name)
    {
        $this->setEnctypeMultipart();

        return $this->addComponent($name, new Element\Input\File($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Input\Hidden
     */
    public function addInputHidden($name)
    {
        return $this->addComponent($name, new Element\Input\Hidden($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Input\Image
     */
    public function addInputImage($name)
    {
        return $this->addComponent($name, new Element\Input\Image($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Input\Month
     */
    public function addInputMonth($name)
    {
        return $this->addComponent($name, new Element\Input\Month($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Input\Number
     */
    public function addInputNumber($name)
    {
        return $this->addComponent($name, new Element\Input\Number($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Input\Password
     */
    public function addInputPassword($name)
    {
        return $this->addComponent($name, new Element\Input\Password($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Input\Radio
     */
    public function addInputRadio($name)
    {
        return $this->addComponent($name, new Element\Input\Radio($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Input\Range
     */
    public function addInputRange($name)
    {
        return $this->addComponent($name, new Element\Input\Range($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Input\Reset
     */
    public function addInputReset($name)
    {
        return $this->addComponent($name, new Element\Input\Reset($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Input\Search
     */
    public function addInputSearch($name)
    {
        return $this->addComponent($name, new Element\Input\Search($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Input\Submit
     */
    public function addInputSubmit($name)
    {
        return $this->addComponent($name, new Element\Input\Submit($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Input\Tel
     */
    public function addInputTel($name)
    {
        return $this->addComponent($name, new Element\Input\Tel($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Input\Text
     */
    public function addInputText($name)
    {
        return $this->addComponent($name, new Element\Input\Text($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Input\Time
     */
    public function addInputTime($name)
    {
        return $this->addComponent($name, new Element\Input\Time($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Input\Url
     */
    public function addInputUrl($name)
    {
        return $this->addComponent($name, new Element\Input\Url($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Input\Week
     */
    public function addInputWeek($name)
    {
        return $this->addComponent($name, new Element\Input\Week($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Select\SingleSelect
     */
    public function addSingleSelect($name)
    {
        return $this->addComponent($name, new Element\Select\SingleSelect($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Select\MultipleSelect
     */
    public function addMultipleSelect($name)
    {
        return $this->addComponent($name, new Element\Select\MultipleSelect($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Element\Textarea
     */
    public function addTextarea($name)
    {
        return $this->addComponent($name, new Element\Textarea($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Group\CheckboxGroup
     */
    public function addCheckboxGroup($name)
    {
        return $this->addComponent($name, new Group\CheckboxGroup($this, $name));
    }

    /**
     * @param string $name
     *
     * @return Group\RadioGroup
     */
    public function addRadioGroup($name)
    {
        return $this->addComponent($name, new Group\RadioGroup($this, $name));
    }

    /**
     * @param string $action
     *
     * @return \Brick\Form\Form
     */
    public function setAction($action)
    {
        $this->getTag()->setAttribute('action', $action);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAction()
    {
        return $this->getTag()->getAttribute('action');
    }

    /**
     * @return \Brick\Form\Form
     */
    public function setMethodGet()
    {
        $this->getTag()->setAttribute('method', 'get');

        return $this;
    }

    /**
     * @return \Brick\Form\Form
     */
    public function setMethodPost()
    {
        $this->getTag()->setAttribute('method', 'post');

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMethod()
    {
        return $this->getTag()->getAttribute('method');
    }

    /**
     * Returns whether the form method is get. This is the default if no method is set.
     *
     * @return boolean
     */
    public function isMethodGet()
    {
        return ! $this->isMethodPost();
    }

    /**
     * Returns whether the form method is post.
     *
     * @return boolean
     */
    public function isMethodPost()
    {
        return $this->getMethod() == 'post';
    }

    /**
     * @return \Brick\Form\Form
     */
    public function setEnctypeUrlencoded()
    {
        $this->getTag()->setAttribute('enctype', self::ENCTYPE_URLENCODED);

        return $this;
    }

    /**
     * @return \Brick\Form\Form
     */
    public function setEnctypeMultipart()
    {
        $this->getTag()->setAttribute('enctype', self::ENCTYPE_MULTIPART);

        return $this;
    }

    /**
     * @return \Brick\Form\Form
     */
    public function setEnctypeTextPlain()
    {
        $this->getTag()->setAttribute('enctype', self::ENCTYPE_PLAIN);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEnctype()
    {
        return $this->getTag()->getAttribute('enctype');
    }

    /**
     * @todo sanity check: should not be used for 'method', 'enctype', etc.?
     *
     * @param string $name
     * @param string $value
     *
     * @return static
     */
    public function setAttribute($name, $value)
    {
        $this->getTag()->setAttribute($name, $value);

        return $this;
    }

    /**
     * @return string
     */
    public function openTag()
    {
        return $this->getTag()->renderOpeningTag();
    }

    /**
     * @return string
     */
    public function closeTag()
    {
        return $this->getTag()->renderClosingTag();
    }

    /**
     * Populates the form with the request data.
     *
     * @param array $data
     *
     * @return \Brick\Form\Form
     */
    public function populate(array $data)
    {
        $this->resetErrors();

        foreach ($this->components as $name => $component) {
            $value = isset($data[$name]) ? $data[$name] : null;
            $component->populate($value);
        }

        return $this;
    }

    /**
     * Checks if the submitted data is valid for the given Request, and populates the form.
     *
     * @param \Brick\Http\Request $request
     *
     * @return \Brick\Form\Form
     */
    public function populateFromRequest(Request $request)
    {
        return $this->populate(
            $this->isMethodPost()
                ? $request->getPost()
                : $request->getQuery()
        );
    }

    /**
     * Returns whether the form is valid.
     *
     * @return boolean
     */
    public function isValid()
    {
        if ($this->hasErrors()) {
            return false;
        }

        foreach ($this->components as $component) {
            if ($component->hasErrors()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns all form-level errors and component-level errors.
     *
     * @return string[]
     */
    public function getAllErrors()
    {
        $errors = $this->getErrors();

        foreach ($this->components as $component) {
            $errors = array_merge($errors, $component->getErrors());
        }

        return $errors;
    }
}
