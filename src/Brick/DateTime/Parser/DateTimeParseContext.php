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
        $this->active = new DateTimeParseContext($this->text, $this->position, $this->active);
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
            $this->active->parent->parsed = array_merge($this->active->parent->parsed, $this->active->parsed);
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
        $nextChars = substr($this->text, $this->position, $charCount);
        $this->active->position += strlen($nextChars);

        return $nextChars;
    }

    /**
     * @return string
     */
    public function getNextDigits()
    {
        if (preg_match('/[0-9]+/', $this->text, $matches, PREG_OFFSET_CAPTURE, $this->position) == 0) {
            return '';
        }

        list ($digits, $position) = $matches[0];

        if ($position != $this->active->position) {
            return '';
        }

        $this->active->position += strlen($digits);

        return $digits;
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
