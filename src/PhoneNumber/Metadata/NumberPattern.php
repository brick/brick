<?php

namespace Brick\PhoneNumber\Metadata;

/**
 * Represents number pattern metadata.
 *
 * This class is internal to the PhoneNumber library, and is not part of the public API.
 */
class NumberPattern extends AbstractMetadata
{
    /**
     * @Pattern
     *
     * @var string|null
     */
    public $nationalNumberPattern;

    /**
     * @Pattern
     *
     * @var string|null
     */
    public $possibleNumberPattern;

    /**
     * @var string|null
     */
    public $exampleNumber;
}
