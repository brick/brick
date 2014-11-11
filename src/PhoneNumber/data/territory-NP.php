<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'NP',
   'countryCode' => '977',
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
       'pattern' => '(1)(\\d{7})',
       'leadingDigits' => 
      array (
        0 => '1[2-6]',
      ),
       'format' => '$1-$2',
       'intlFormat' => 
      array (
      ),
    )),
    1 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{2})(\\d{6})',
       'leadingDigits' => 
      array (
        0 => '1[01]|[2-8]|9(?:[1-69]|7[15-9])',
      ),
       'format' => '$1-$2',
       'intlFormat' => 
      array (
      ),
    )),
    2 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(9\\d{2})(\\d{7})',
       'leadingDigits' => 
      array (
        0 => '9(?:7[45]|8)',
      ),
       'format' => '$1-$2',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[1-8]\\d{7}|9(?:[1-69]\\d{6}|7[2-6]\\d{5,7}|8\\d{8})',
     'possibleNumberPattern' => '\\d{6,10}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:1[0124-6]|2[13-79]|3[135-8]|4[146-9]|5[135-7]|6[13-9]|7[15-9]|8[1-46-9]|9[1-79])\\d{6}',
     'possibleNumberPattern' => '\\d{6,8}',
     'exampleNumber' => '14567890',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '9(?:7[45]|8[0145])\\d{7}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '9841234567',
  )),
   'pager' => NULL,
   'tollFree' => NULL,
   'premiumRate' => NULL,
   'sharedCost' => NULL,
   'personalNumber' => NULL,
   'voip' => NULL,
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:0[0-3]|12)',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '112',
  )),
));