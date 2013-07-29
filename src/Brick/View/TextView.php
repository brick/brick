<?php

namespace Brick\View;

/**
 * Simple text view, to be used by controllers that need to render plain text in a layout.
 * @todo require html escaping?
 */
class TextView implements View
{
    /**
     * @var string
     */
    protected $text;

    /**
     * Class constructor.
     *
     * @param string $text
     */
    public function __construct($text)
    {
        $this->text = (string) $text;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        return $this->text;
    }
}
