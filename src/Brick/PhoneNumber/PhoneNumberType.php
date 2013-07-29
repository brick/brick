<?php

namespace Brick\PhoneNumber;

/**
 * Phone number type constants.
 */
class PhoneNumberType
{
    const FIXED_LINE = 'FIXED_LINE';

    const MOBILE = 'MOBILE';

    /**
     * In some regions (e.g. the USA), it is impossible to distinguish between fixed-line and
     * mobile numbers by looking at the phone number itself.
     */
    const FIXED_LINE_OR_MOBILE = 'FIXED_LINE_OR_MOBILE';

    const TOLL_FREE = 'TOLL_FREE';

    const PREMIUM_RATE = 'PREMIUM_RATE';

    /**
     * The cost of this call is shared between the caller and the recipient, and is hence typically
     * less than PREMIUM_RATE calls. See // http://en.wikipedia.org/wiki/Shared_Cost_Service for
     * more information.
     */
    const SHARED_COST = 'SHARED_COST';

    /**
     * Voice over IP numbers. This includes TSoIP (Telephony Service over IP).
     */
    const VOIP = 'VOIP';

    /**
     * A personal number is associated with a particular person, and may be routed to either a
     * MOBILE or FIXED_LINE number. Some more information can be found here:
     * http://en.wikipedia.org/wiki/Personal_Numbers
     */
    const PERSONAL_NUMBER = 'PERSONAL_NUMBER';

    const PAGER = 'PAGER';

    /**
     * Used for "Universal Access Numbers" or "Company Numbers". They may be further routed to
     * specific offices, but allow one number to be used for a company.
     */
    const UAN = 'UAN';

    /**
     * Used for "Voice Mail Access Numbers".
     */
    const VOICEMAIL = 'VOICEMAIL';

    /**
     * A phone number is of type UNKNOWN when it does not fit any of the known patterns for a
     * specific region.
     */
    const UNKNOWN = 'UNKNOWN';
}
