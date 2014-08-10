<?php

namespace Brick\DateTime\Parser;

/**
 * Aggregates a collection of parsers that together match a string.
 */
class CompositeParser extends ContextParser
{
    /**
     * The list of parsers to use.
     *
     * @var ContextParser[]
     */
    private $parsers;

    /**
     * Whether this parser is optional.
     *
     * @var boolean
     */
    private $optional;

    /**
     * Class constructor.
     *
     * @param ContextParser[] $parsers
     * @param boolean          $optional
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
