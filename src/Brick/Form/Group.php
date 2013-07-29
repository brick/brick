<?php

namespace Brick\Form;

/**
 * Base class for groups of form elements.
 */
abstract class Group extends Component
{
    /**
     * @var string
     */
    protected $name;

    /**
     * {@inheritdoc}
     */
    protected function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return \Brick\Form\Element[]
     */
    abstract public function getElements();
}
