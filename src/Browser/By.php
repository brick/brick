<?php

namespace Brick\Browser;

/**
 * Mechanism used to locate elements within a document.
 */
abstract class By implements Target
{
    /**
     * Returns a By which locates elements by id.
     *
     * @param string $id
     * @return By\ById
     */
    public static function id($id)
    {
        return new By\ById($id);
    }

    /**
     * Returns a By which locates elements by name.
     *
     * @param string $name
     * @return By\ByName
     */
    public static function name($name)
    {
        return new By\ByName($name);
    }

    /**
     * Returns a By which locates elements by tag name.
     *
     * @param string $tagName
     * @return By\ByTagName
     */
    public static function tagName($tagName)
    {
        return new By\ByTagName($tagName);
    }

    /**
     * Returns a By which locates elements by class name.
     *
     * @param string $className
     * @return By\ByClassName
     */
    public static function className($className)
    {
        return new By\ByClassName($className);
    }

    /**
     * Returns a By which locates A elements by the exact text they display.
     *
     * @param string $text
     * @return By\ByLinkText
     */
    public static function linkText($text)
    {
        return new By\ByLinkText($text);
    }

    /**
     * Returns a By which locates A elements by the exact text they display.
     *
     * @param string $text
     * @return By\ByPartialLinkText
     */
    public static function partialLinkText($text)
    {
        return new By\ByPartialLinkText($text);
    }

    /**
     * Returns a By which locates elements by CSS selector.
     *
     * @param string $selector
     * @return By\ByCssSelector
     */
    public static function cssSelector($selector)
    {
        return new By\ByCssSelector($selector);
    }

    /**
     * Returns a By which locates elements by XPath.
     *
     * @param string $xPath
     * @return By\ByXPath
     */
    public static function xPath($xPath)
    {
        return new By\ByXPath($xPath);
    }

    /**
     * @param By[] $bys
     * @return By\ByChain
     */
    public static function chain(array $bys)
    {
        return new By\ByChain($bys);
    }

    /**
     * Returns an array of elements matching this By locator appearing under any of the elements in the given list.
     *
     * This method is allowed to return duplicate elements; duplicates will be removed by the SearchContext.
     *
     * @todo use inheritdoc in subclasses when PHPStorm understands it properly.
     * @see http://youtrack.jetbrains.com/issue/WI-18266
     *
     * @param \DOMElement[] $elements
     * @return \DOMElement[]
     */
    abstract public function findElements(array $elements);

    /**
     * {@inheritdoc}
     */
    public function getTargetElement(Browser $browser)
    {
        return $browser->find($this)->one();
    }
}
