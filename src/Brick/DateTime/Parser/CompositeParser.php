<?php

namespace Brick\DateTime\Parser;

/**
 * Composite parser.
 */
class CompositeParser extends DateTimeParser
{
    /**
     * The list of parsers to use.
     *
     * @var DateTimeParser[]
     */
    private $parsers;

    /**
     * Whether this parser is optional.
     *
     * @var bool
     */
    private $optional;

    /**
     * Class constructor.
     *
     * @param DateTimeParser[] $parsers
     * @param bool $optional
     */
    public function __construct(array $parsers, $optional)
    {
        $this->parsers = $parsers;
        $this->optional = $optional;
    }

    /**
     * {@inheritdoc}
     */
    public function parseInto(DateTimeParseContext $context)
    {
        if ($this->optional) {
            $context->startOptional();
        }

        $successful = true;

        foreach ($this->parsers as $parser) {
            if (! $parser->parseInto($context)) {
                $successful = false;
                break;
            }
        }

        if ($this->optional) {
            $context->endOptional($successful);
        }

        return $successful || $this->optional;
    }
}
