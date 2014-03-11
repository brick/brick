<?php

namespace Brick\Form\Element;

use Brick\Form\Attribute\ValueAttribute;
use Brick\Form\Element;
use Brick\Html\ContainerTag;

/**
 * Represents a button element.
 */
abstract class Button extends Element
{
    use ValueAttribute;

    /**
     * @var \Brick\Html\ContainerTag|null
     */
    private $tag = null;

    /**
     * {@inheritdoc}
     */
    protected function getTag()
    {
        if ($this->tag === null) {
            $this->tag = new ContainerTag('button', [
                'type' => $this->getType()
            ]);
        }

        return $this->tag;
    }

    /**
     * Returns the type of this button.
     *
     * @return string
     */
    abstract protected function getType();
}
