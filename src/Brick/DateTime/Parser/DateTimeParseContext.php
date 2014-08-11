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
     * The currently active context, used by the outermost context.
     *
     * @var DateTimeParseContext
     */
    private $active;

    /**
     * The parent context, null for the outermost context.
     *
     * @var DateTimeParseContext|null
     */
    private $parent;

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
    private $position;

    /**
     * Private constructor. Use create() to create an instance.
     *
     * @param string                    $text
     * @param integer                   $position
     * @param DateTimeParseContext|null $parent
     */
    private function __construct($text, $position = 0, DateTimeParseContext $parent = null)
    {
        $this->active   = $this;
        $this->text     = $text;
        $this->length   = strlen($text);
        $this->position = $position;
        $this->parent   = $parent;
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
        return $this->active->position;
    }

    /**
     * Marks the start of the parsing of an optional segment of the input.
     *
     * @return void
     */
    public function startOptional()
    {
        $this->active = new DateTimeParseContext($this->text, $this->active->position, $this->active);
    }

    /**
     * Marks the end of the parsing of an optional segment of the input.
     *
     * @param boolean $successful Whether the optional segment was successfully parsed.
     *
     * @return void
     *
     * @throws DateTimeParseException
     */
    public function endOptional($successful)
    {
        if (! $this->active->parent) {
            throw new DateTimeParseException(
                'Cannot call endOptional() as there was no previous call to startOptional()'
            );
        }

        if ($successful) {
            $this->active->parent->parsed = $this->active->parsed + $this->active->parent->parsed;
            $this->active->parent->position = $this->active->position;
        }

        $this->active = $this->active->parent;
    }

    /**
     * @param string $field
     * @param mixed  $value
     *
     * @return void
     */
    public function setParsedField($field, $value)
    {
        $this->active->parsed[$field] = $value;
    }

    /**
     * @param integer $charCount
     *
     * @return string
     */
    public function getNextChars($charCount)
    {
        if ($this->active->position === $this->active->length) {
            return '';
        }

        $nextChars = substr($this->text, $this->active->position, $charCount);
        $this->active->position += strlen($nextChars);

        return $nextChars;
    }

    /**
     * @param string $pattern A valid regular expression.
     *
     * @return string
     */
    public function getNextCharsMatching($pattern)
    {
        $pattern = '/' . str_replace('/', '\/', $pattern) . '/';

        if (preg_match($pattern, $this->text, $matches, PREG_OFFSET_CAPTURE, $this->active->position) !== 1) {
            return '';
        }

        list ($string, $position) = $matches[0];

        if ($position !== $this->active->position) {
            return '';
        }

        $this->active->position += strlen($string);

        return $string;
    }

    /**
     * @param string $pattern A valid regular expression.
     *
     * @return array The matches, empty if the pattern didn't match.
     */
    public function match($pattern)
    {
        $pattern = '/' . str_replace('/', '\/', $pattern) . '/';

        if (preg_match($pattern, $this->text, $matches, PREG_OFFSET_CAPTURE, $this->active->position) !== 1) {
            return [];
        }

        list ($string, $position) = $matches[0];

        if ($position !== $this->active->position) {
            return [];
        }

        $this->active->position += strlen($string);

        foreach ($matches as & $match) {
            $match = $match[0];
        }

        return $matches;
    }

    /**
     * @return string
     */
    public function getNextDigits()
    {
        return $this->getNextCharsMatching('[0-9]+');
    }

    /**
     * @return boolean
     */
    public function hasNext()
    {
        return $this->position < $this->length;
    }

    /**
     * @return DateTimeParseResult
     *
     * @throws DateTimeParseException
     */
    public function toParseResult()
    {
        if ($this->active->parent) {
            throw new DateTimeParseException('A call to startOptional() has not been ended with endOptional()');
        }

        if ($this->active->hasNext()) {
            throw DateTimeParseException::unexpectedContent($this);
        }

        return new DateTimeParseResult($this->parsed);
    }
}
