<?php

namespace Brick\DateTime\Parser;

/**
 * Context object used during date and time parsing.
 */
class DateTimeParseContext
{
    /**
     * The list of parsed data.
     *
     * @var array
     */
    private $parsed = [];

    /**
     * The text to parse.
     *
     * @var string
     */
    private $text;

    /**
     * The length of the text to parse.
     *
     * @var integer
     */
    private $length;

    /**
     * The current position in the text to parse.
     *
     * @var integer
     */
    private $position = 0;

    /**
     * Private constructor. Use create() to create an instance.
     *
     * @param string $text
     */
    public function __construct($text)
    {
        $this->text   = $text;
        $this->length = strlen($text);
    }

    /**
     * Creates a `DateTimeParseContext`.
     *
     * @param string $text
     *
     * @return DateTimeParseContext
     */
    public static function create($text)
    {
        return new DateTimeParseContext($text);
    }

    /**
     * Returns the text being parsed.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Returns the current string pointer position.
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $name  The field name.
     * @param string $value The field value.
     *
     * @return void
     */
    public function setParsedField($name, $value)
    {
        $this->parsed[$name][] = $value;
    }

    /**
     * @param string $pattern A valid regular expression.
     *
     * @return array The matches, empty if the pattern didn't match.
     */
    public function match($pattern)
    {
        $pattern = '/' . str_replace('/', '\/', $pattern) . '/';

        if (preg_match($pattern, $this->text, $matches, PREG_OFFSET_CAPTURE, $this->position) !== 1) {
            return [];
        }

        list ($string, $position) = $matches[0];

        if ($position !== $this->position) {
            return [];
        }

        $this->position += strlen($string);

        foreach ($matches as & $match) {
            $match = $match[0];
        }

        return $matches;
    }

    /**
     * @return DateTimeParseResult
     *
     * @throws DateTimeParseException
     */
    public function toParseResult()
    {
        if ($this->position !== $this->length) {
            throw DateTimeParseException::unexpectedContent($this);
        }

        return new DateTimeParseResult($this->parsed);
    }
}
