<?php

namespace Brick\Browser\Wrapper;

use Brick\Browser\Element;
use Brick\Browser\Exception\NoSuchElementException;
use Brick\Browser\Exception\UnexpectedElementException;

/**
 * Base class for form elements.
 */
abstract class FormControl extends AbstractWrapper
{
    /**
     * @return Form
     *
     * @throws UnexpectedElementException If the form attribute does not target a form.
     * @throws NoSuchElementException     IF the element does not belong to a form.
     */
    public function getForm()
    {
        $element = $this->element->getDomElement();
        $document = $element->ownerDocument;

        if ($element->hasAttribute('form')) {
            $form = $document->getElementById($element->getAttribute('form'));
            if (! $form) {
                throw new NoSuchElementException('Element has a form attribute that targets an id that does not exist');
            }
            if (strtolower($form->tagName) != 'form') {
                throw new UnexpectedElementException('Element has a form attribute that does not target a form');
            }

            $form = new Element($form);
        } else {
            $form = $this->element->getParent('form');
        }

        return Form::create($form);
    }

    /**
     * Returns the name of the control.
     *
     * @return string
     */
    public function getName()
    {
        return $this->element->getAttribute('name');
    }
}
