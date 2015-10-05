<?php

namespace Brick\Html;

/**
 * Represents an HTML container tag, such as <a></a>.
 */
class ContainerTag extends Tag
{
    /**
     * @var string
     */
    private $content = '';

    /**
     * @param string $content
     *
     * @return static
     */
    public function setTextContent($content)
    {
        $this->content = htmlspecialchars($content);

        return $this;
    }

    /**
     * @param string $content
     *
     * @return static
     */
    public function setHtmlContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return $this->content == '';
    }

    /**
     * @return string
     */
    public function renderOpeningTag()
    {
        return sprintf('<%s%s>', $this->name, $this->renderAttributes());
    }

    /**
     * @return string
     */
    public function renderClosingTag()
    {
        return sprintf('</%s>', $this->name);
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        return $this->renderOpeningTag() . $this->content . $this->renderClosingTag();
    }
}
