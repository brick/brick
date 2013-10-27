<?php

namespace Brick\Form;

use Brick\Html\ContainerTag;
use Brick\Http\Request;

/**
 * Represents an HTML form.
 */
class Form extends Base
{
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
     * @param Component $component
     * @return Component
     */
    private function addComponent(Component $component)
    {
        // @todo key = component name?
        $this->components[] = $component;
        return $component;
    }

    /**
     * @return Component[]
     */
    public function getComponents()
    {
        return $this->components;
    }

    /**
     * @param string $name
     * @return Component
     * @throws \RuntimeException
     */
    public function getComponent($name)
    {
        // @todo direct key lookup possible if we index by name

        foreach ($this->components as $component) {
            if ($component->getName() == $name) {
                return $component;
            }
        }

        throw new \RuntimeException('No component named ' . var_export($name, true));
    }

    /**
     * @param string $name
     * @return Element\Button\Button
     */
    public function addButtonButton($name)
    {
        return $this->addComponent(new Element\Button\Button($this, $name));
    }

    /**
     * @param string $name
     * @return Element\Button\Reset
     */
    public function addButtonReset($name)
    {
        return $this->addComponent(new Element\Button\Reset($this, $name));
    }

    /**
     * @param string $name
     * @return Element\Button\Submit
     */
    public function addButtonSubmit($name)
    {
        return $this->addComponent(new Element\Button\Submit($this, $name));
    }

    /**
     * @param string $name
     * @return Element\Input\Button
     */
    public function addInputButton($name)
    {
        return $this->addComponent(new Element\Input\Button($this, $name));
    }

    /**
     * @param string $name
     * @return Element\Input\Checkbox
     */
    public function addInputCheckbox($name)
    {
        return $this->addComponent(new Element\Input\Checkbox($this, $name));
    }

    /**
     * @param string $name
     * @return Element\Input\Email
     */
    public function addInputEmail($name)
    {
        return $this->addComponent(new Element\Input\Email($this, $name));
    }

    /**
     * @param string $name
     * @return Element\Input\File
     */
    public function addInputFile($name)
    {
        $this->setEnctypeMultipart();

        return $this->addComponent(new Element\Input\File($this, $name));
    }

    /**
     * @param string $name
     * @return Element\Input\Hidden
     */
    public function addInputHidden($name)
    {
        return $this->addComponent(new Element\Input\Hidden($this, $name));
    }

    /**
     * @param string $name
     * @return Element\Input\Image
     */
    public function addInputImage($name)
    {
        return $this->addComponent(new Element\Input\Image($this, $name));
    }

    /**
     * @param string $name
     * @return Element\Input\Password
     */
    public function addInputPassword($name)
    {
        return $this->addComponent(new Element\Input\Password($this, $name));
    }

    /**
     * @todo maybe we could remove this method? Radio buttons only make sense in groups...
     *
     * @param string $name
     * @return Element\Input\Radio
     */
    public function addInputRadio($name)
    {
        return $this->addComponent(new Element\Input\Radio($this, $name));
    }

    /**
     * @param string $name
     * @return Element\Input\Reset
     */
    public function addInputReset($name)
    {
        return $this->addComponent(new Element\Input\Reset($this, $name));
    }

    /**
     * @param string $name
     * @return Element\Input\Submit
     */
    public function addInputSubmit($name)
    {
        return $this->addComponent(new Element\Input\Submit($this, $name));
    }

    /**
     * @param string $name
     * @return Element\Input\Text
     */
    public function addInputText($name)
    {
        return $this->addComponent(new Element\Input\Text($this, $name));
    }

    /**
     * @param string $name
     * @return Element\Select\SingleSelect
     */
    public function addSingleSelect($name)
    {
        return $this->addComponent(new Element\Select\SingleSelect($this, $name));
    }

    /**
     * @param string $name
     * @return Element\Select\MultipleSelect
     */
    public function addMultipleSelect($name)
    {
        return $this->addComponent(new Element\Select\MultipleSelect($this, $name));
    }

    /**
     * @param string $name
     * @return Element\Textarea
     */
    public function addTextarea($name)
    {
        return $this->addComponent(new Element\Textarea($this, $name));
    }

    /**
     * @param string $name
     * @return Group\CheckboxGroup
     */
    public function addCheckboxGroup($name)
    {
        return $this->addComponent(new Group\CheckboxGroup($this, $name));
    }

    /**
     * @param string $name
     * @return Group\RadioGroup
     */
    public function addRadioGroup($name)
    {
        return $this->addComponent(new Group\RadioGroup($this, $name));
    }

    /**
     * @param string $action
     * @return Form
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
     * @return Form
     */
    public function setMethodGet()
    {
        $this->getTag()->setAttribute('method', 'get');
        return $this;
    }

    /**
     * @return Form
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
     * @return bool
     */
    public function isMethodGet()
    {
        return ! $this->isMethodPost();
    }

    /**
     * Returns whether the form method is post.
     *
     * @return bool
     */
    public function isMethodPost()
    {
        return $this->getMethod() == 'post';
    }

    /**
     * @return Form
     */
    public function setEnctypeUrlencoded()
    {
        $this->getTag()->setAttribute('enctype', 'application/x-www-form-urlencoded');
        return $this;
    }

    /**
     * @return Form
     */
    public function setEnctypeMultipart()
    {
        $this->getTag()->setAttribute('enctype', 'multipart/form-data');
        return $this;
    }

    /**
     * @return Form
     */
    public function setEnctypeTextPlain()
    {
        $this->getTag()->setAttribute('enctype', 'text/plain');
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
     * Checks if the submitted data is valid for this form, and populates the form.
     *
     * @param array $data
     * @return bool
     */
    public function isValid(array $data)
    {
        $valid = true;

        foreach ($this->components as $component) {
            $name = $component->getName();
            $value = isset($data[$name]) ? $data[$name] : null;
            $component->validate($value);

            if ($component->hasErrors()) {
                $valid = false;
            }
        }

        return $valid;
    }

    /**
     * Checks if the submitted data is valid for the given Request, and populates the form.
     *
     * @param \Brick\Http\Request $request
     * @return boolean
     */
    public function isValidForRequest(Request $request)
    {
        $data = $this->isMethodPost() ? $request->getPost() : $request->getQuery();

        return $this->isValid($data);
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
