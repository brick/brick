<?php

namespace Brick\PhoneNumber\Metadata;

/**
 * Represents number format metadata.
 *
 * This class is internal to the PhoneNumber library, and is not part of the public API.
 */
class NumberFormat extends AbstractMetadata
{
    /**
     * @var string|null
     */
    public $nationalPrefixFormattingRule = null;

    /**
     * @Boolean
     *
     * @var boolean
     */
    public $nationalPrefixOptionalWhenFormatting = false;

    /**
     * @var string|null
     */
    public $carrierCodeFormattingRule = null;

    /**
     * @Required @Pattern
     *
     * @var string
     */
    public $pattern;

    /**
     * @Pattern @Multiple
     *
     * @var string[]
     */
    public $leadingDigits = array();

    /**
     * @Required
     *
     * @var string
     */
    public $format;

    /**
     * @Multiple
     *
     * @var string[]
     */
    public $intlFormat = array();
}
