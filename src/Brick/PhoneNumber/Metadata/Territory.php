<?php

namespace Brick\PhoneNumber\Metadata;

/**
 * Represents territory metadata.
 *
 * This class is internal to the PhoneNumber library, and is not part of the public API.
 */
class Territory extends AbstractMetadata
{
    /**
     * @Required
     *
     * @var string
     */
    public $id;

    /**
     * @Required
     *
     * @var string
     */
    public $countryCode;

    /**
     * @Boolean
     *
     * @var boolean
     */
    public $mainCountryForCode = false;

    /**
     * @Pattern
     *
     * @var string|null
     */
    public $leadingDigits = null;

    /**
     * @var string|null
     */
    public $preferredInternationalPrefix = null;

    /**
     * @Pattern
     *
     * @var string|null
     */
    public $internationalPrefix = null;

    /**
     * @var string|null
     */
    public $nationalPrefix = null;

    /**
     * @Pattern
     *
     * @var string|null
     */
    public $nationalPrefixForParsing = null;

    /**
     * @var string|null
     */
    public $nationalPrefixTransformRule = null;

    /**
     * @var string|null
     */
    public $preferredExtnPrefix = null;

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
     * @Boolean
     *
     * @var boolean
     */
    public $leadingZeroPossible = false;

    /**
     * @var string|null
     */
    public $carrierCodeFormattingRule = null;

    /**
     * @var NumberFormat[]
     */
    public $availableFormats = array();

    /**
     * @var NumberPattern|null
     */
    public $generalDesc = null;

    /**
     * @var NumberPattern|null
     */
    public $noInternationalDialling = null;

    /**
     * @var NumberPattern|null
     */
    public $areaCodeOptional = null;

    /**
     * @var NumberPattern|null
     */
    public $fixedLine = null;

    /**
     * @var NumberPattern|null
     */
    public $mobile = null;

    /**
     * @var NumberPattern|null
     */
    public $pager = null;

    /**
     * @var NumberPattern|null
     */
    public $tollFree = null;

    /**
     * @var NumberPattern|null
     */
    public $premiumRate = null;

    /**
     * @var NumberPattern|null
     */
    public $sharedCost = null;

    /**
     * @var NumberPattern|null
     */
    public $personalNumber = null;

    /**
     * @var NumberPattern|null
     */
    public $voip = null;

    /**
     * @var NumberPattern|null
     */
    public $uan = null;

    /**
     * @var NumberPattern|null
     */
    public $voicemail = null;

    /**
     * @var NumberPattern|null
     */
    public $shortCode = null;

    /**
     * @var NumberPattern|null
     */
    public $emergency = null;
}
