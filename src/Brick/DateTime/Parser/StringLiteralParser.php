<?php

namespace Brick\DateTime\Parser;

/**
 * Matches an exact string.
 */
class StringLiteralParser extends ContextParser
{
    /**
     * @var string
     */
    private $literal;

    /**
     * Class constructor.
     *
     * @param string $literal
     */
    public function __construct($literal)
    {
        $this->literal = $literal;
    }

    /**
     * {@inheritdoc}
     */
    public function parseInto(DateTimeParseContext $context)
    {
        return $context->getNextChars(strlen($this->literal)) === $this->literal;
    }
}
