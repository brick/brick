<?php

namespace Brick\DateTime\Parser;

/**
 * Matches a regular expression pattern to a set of date-time fields.
 */
class PatternParser extends ContextParser
{
    /**
     * @var string
     */
    private $pattern = '';

    /**
     * @var array
     */
    private $fields = [];

    /**
     * @var integer
     */
    private $optionalLevel = 0;

    /**
     * @param PatternParser $parser
     *
     * @return static
     */
    public function append(PatternParser $parser)
    {
        $this->pattern .= $parser->toPattern();
        $this->fields = array_merge($this->fields, $parser->fields);

        return $this;
    }

    /**
     * @param string $literal
     *
     * @return static
     */
    public function appendLiteral($literal)
    {
        $this->pattern .= preg_quote($literal);

        return $this;
    }

    /**
     * @param string $pattern
     * @param string $field
     *
     * @return static
     */
    public function appendCapturePattern($pattern, $field)
    {
        $this->pattern .= '(' . $pattern . ')';
        $this->fields[] = $field;

        return $this;
    }

    /**
     * @return static
     */
    public function startGroup()
    {
        $this->pattern .= '(?:';

        return $this;
    }

    /**
     * @return static
     */
    public function endGroup()
    {
        $this->pattern .= ')';

        return $this;
    }

    /**
     * @return static
     */
    public function appendOr()
    {
        $this->pattern .= '|';

        return $this;
    }

    /**
     * @return static
     */
    public function startOptional()
    {
        $this->pattern .= '(?:';
        $this->optionalLevel++;

        return $this;
    }

    /**
     * @return static
     */
    public function endOptional()
    {
        if ($this->optionalLevel === 0) {
            throw new \RuntimeException('Cannot call endOptional() without a call to startOptional() first.');
        }

        $this->pattern .= ')?';
        $this->optionalLevel--;

        return $this;
    }

    /**
     * @return string
     */
    private function toPattern()
    {
        if ($this->optionalLevel !== 0) {
            throw new \RuntimeException('A call to startOptional() has not been followed by endOptional().');
        }

        return $this->pattern;
    }

    /**
     * {@inheritdoc}
     */
    public function parseInto(DateTimeParseContext $context)
    {
        $matches = $context->match($this->toPattern());

        if (! $matches) {
            return false;
        }

        $index = 1;
        foreach ($this->fields as $field) {
            if (isset($matches[$index]) && $matches[$index] !== '') {
                $context->setParsedField($field, $matches[$index]);
            }
            $index++;
        }

        return true;
    }
}
