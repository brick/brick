<?php

namespace Brick\Html;

/**
 * Represents an HTML self closing tag, such as <img />.
 */
class SelfClosingTag extends Tag
{
    /**
     * {@inheritdoc}
     */
    public function render()
    {
        return sprintf('<%s%s />', $this->name, $this->renderAttributes());
    }
}
