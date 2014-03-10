<?php

namespace Brick\Html;

/**
 * Represents an HTML tag.
 */
abstract class Tag
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Class constructor.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param string $name
     *
     * @return boolean
     */
    public function hasAttribute($name)
    {
        return isset($this->attributes[$name]);
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function getAttribute($name)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return static
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * @param array $attributes
     *
     * @return static
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes + $this->attributes;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return static
     */
    public function removeAttribute($name)
    {
        unset($this->attributes[$name]);

        return $this;
    }

    /**
     * @return string
     */
    protected function renderAttributes()
    {
        $result = '';

        foreach ($this->attributes as $name => $value) {
            $result .= sprintf(' %s="%s"', $name, htmlspecialchars($value));
        }

        return $result;
    }

    /**
     * @return string
     */
    abstract public function render();

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
