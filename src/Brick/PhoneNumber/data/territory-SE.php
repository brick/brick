<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'SE',
   'countryCode' => '46',
   'mainCountryForCode' => false,
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => NULL,
   'internationalPrefix' => '00',
   'nationalPrefix' => '0',
   'nationalPrefixForParsing' => NULL,
   'nationalPrefixTransformRule' => NULL,
   'preferredExtnPrefix' => NULL,
   'nationalPrefixFormattingRule' => '$NP$FG',
   'nationalPrefixOptionalWhenFormatting' => false,
   'leadingZeroPossible' => false,
   'carrierCodeFormattingRule' => NULL,
   'availableFormats' => 
  array (
    0 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(8)(\\d{2,3})(\\d{2,3})(\\d{2})',
       'leadingDigits' => 
      array (
        0 => '8',
      ),
       'format' => '$1-$2 $3 $4',
       'intlFormat' => 
      array (
        0 => '$1 $2 $3 $4',
      ),
    )),
    1 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '([1-69]\\d)(\\d{2,3})(\\d{2})(\\d{2})',
       'leadingDigits' => 
      array (
        0 => '1[013689]|2[0136]|3[1356]|4[0246]|54|6[03]|90',
      ),
       'format' => '$1-$2 $3 $4',
       'intlFormat' => 
      array (
        0 => '$1 $2 $3 $4',
      ),
    )),
    2 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '([1-69]\\d)(\\d{3})(\\d{2})',
       'leadingDigits' => 
      array (
        0 => '1[13689]|2[136]|3[1356]|4[0246]|54|6[03]|90',
      ),
       'format' => '$1-$2 $3',
       'intlFormat' => 
      array (
        0 => '$1 $2 $3',
      ),
    )),
    3 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{3})(\\d{2})(\\d{2})(\\d{2})',
       'leadingDigits' => 
      array (
        0 => '1[2457]|2[2457-9]|3[0247-9]|4[1357-9]|5[0-35-9]|6[124-9]|9(?:[125-8]|3[0-5]|4[0-3])',
      ),
       'format' => '$1-$2 $3 $4',
       'intlFormat' => 
      array (
        0 => '$1 $2 $3 $4',
      ),
    )),
    4 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{3})(\\d{2,3})(\\d{2})',
       'leadingDigits' => 
      array (
        0 => '1[2457]|2[2457-9]|3[0247-9]|4[1357-9]|5[0-35-9]|6[124-9]|9(?:[125-8]|3[0-5]|4[0-3])',
      ),
       'format' => '$1-$2 $3',
       'intlFormat' => 
      array (
        0 => '$1 $2 $3',
      ),
    )),
    5 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(7\\d)(\\d{3})(\\d{2})(\\d{2})',
       'leadingDigits' => 
      array (
        0 => '7',
      ),
       'format' => '$1-$2 $3 $4',
       'intlFormat' => 
      array (
        0 => '$1 $2 $3 $4',
      ),
    )),
    6 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(20)(\\d{2,3})(\\d{2})',
       'leadingDigits' => 
      array (
        0 => '20',
      ),
       'format' => '$1-$2 $3',
       'intlFormat' => 
      array (
        0 => '$1 $2 $3',
      ),
    )),
    7 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(9[034]\\d)(\\d{2})(\\d{2})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '9[034]',
      ),
       'format' => '$1-$2 $3 $4',
       'intlFormat' => 
      array (
        0 => '$1 $2 $3 $4',
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[1-9]\\d{6,9}',
     'possibleNumberPattern' => '\\d{5,10}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:0[1-8]\\d{6}|[136]\\d{5,7}|(?:2[0-35]|4[0-4]|5[0-25-9]|7[13-6]|[89]\\d)\\d{5,6})|2(?:[136]\\d{5,7}|(?:2[0-7]|4[0136-8]|5[0138]|7[018]|8[01]|9[0-57])\\d{5,6})|3(?:[356]\\d{5,7}|(?:0[0-4]|1\\d|2[0-25]|4[056]|7[0-2]|8[0-3]|9[023])\\d{5,6})|4(?:[0246]\\d{5,7}|(?:1[0-8]|3[0135]|5[14-79]|7[0-246-9]|8[0156]|9[0-689])\\d{5,6})|5(?:0[0-6]|[15][0-5]|2[0-68]|3[0-4]|4\\d|6[03-5]|7[013]|8[0-79]|9[01])\\d{5,6}|6(?:[03]\\d{5,7}|(?:1[1-3]|2[0-4]|4[02-57]|5[0-37]|6[0-3]|7[0-2]|8[0247]|9[0-356])\\d{5,6})|8\\d{6,8}|9(?:0\\d{5,7}|(?:1[0-68]|2\\d|3[02-59]|[45][0-4]|[68][01]|7[0135-8])\\d{5,6})',
     'possibleNumberPattern' => '\\d{5,9}',
     'exampleNumber' => '8123456',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '7[0236]\\d{7}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '701234567',
  )),
   'pager' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '74\\d{7}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '741234567',
  )),
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '20\\d{4,7}',
     'possibleNumberPattern' => '\\d{6,9}',
     'exampleNumber' => '201234567',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '9(?:00|39|44)\\d{7}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '9001234567',
  )),
   'sharedCost' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '77\\d{7}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '771234567',
  )),
   'personalNumber' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '75\\d{7}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '751234567',
  )),
   'voip' => NULL,
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '112|90000',
     'possibleNumberPattern' => '\\d{3,5}',
     'exampleNumber' => '112',
  )),
));