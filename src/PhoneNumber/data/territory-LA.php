<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'LA',
   'countryCode' => '856',
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
       'pattern' => '(20)(\\d{2})(\\d{3})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '20',
      ),
       'format' => '$1 $2 $3 $4',
       'intlFormat' => 
      array (
      ),
    )),
    1 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '([2-8]\\d)(\\d{3})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '2[13]|[3-8]',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[2-8]\\d{7,9}',
     'possibleNumberPattern' => '\\d{6,10}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:2[13]|[35-7][14]|41|8[1468])\\d{6}',
     'possibleNumberPattern' => '\\d{6,8}',
     'exampleNumber' => '21212862',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '20(?:2[2389]|5[4-689]|7[6-8]|9[57-9])\\d{6}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '2023123456',
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
     'nationalNumberPattern' => '19[015]',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '190',
  )),
));