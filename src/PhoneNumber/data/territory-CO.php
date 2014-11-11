<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'CO',
   'countryCode' => '57',
   'mainCountryForCode' => false,
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => NULL,
   'internationalPrefix' => '00[579]|#555|#999',
   'nationalPrefix' => '0',
   'nationalPrefixForParsing' => '0([3579]|4(?:44|56))?',
   'nationalPrefixTransformRule' => NULL,
   'preferredExtnPrefix' => NULL,
   'nationalPrefixFormattingRule' => NULL,
   'nationalPrefixOptionalWhenFormatting' => false,
   'leadingZeroPossible' => false,
   'carrierCodeFormattingRule' => NULL,
   'availableFormats' => 
  array (
    0 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '($FG)',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => '$NP$CC $FG',
       'pattern' => '(\\d)(\\d{7})',
       'leadingDigits' => 
      array (
        0 => '1(?:8[2-9]|9[0-3]|[2-7])|[24-8]',
        1 => '1(?:8[2-9]|9(?:09|[1-3])|[2-7])|[24-8]',
      ),
       'format' => '$1 $2',
       'intlFormat' => 
      array (
      ),
    )),
    1 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => '$NP$CC $FG',
       'pattern' => '(\\d{3})(\\d{7})',
       'leadingDigits' => 
      array (
        0 => '3',
      ),
       'format' => '$1 $2',
       'intlFormat' => 
      array (
      ),
    )),
    2 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$NP$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(1)(\\d{3})(\\d{7})',
       'leadingDigits' => 
      array (
        0 => '1(?:80|9[04])',
        1 => '1(?:800|9(?:0[01]|4[78]))',
      ),
       'format' => '$1-$2-$3',
       'intlFormat' => 
      array (
        0 => '$1 $2 $3',
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:[13]\\d{0,3}|[24-8])\\d{7}',
     'possibleNumberPattern' => '\\d{7,11}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[124-8][2-9]\\d{6}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '12345678',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '3(?:0[0-24]|1\\d|2[01])\\d{7}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '3211234567',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1800\\d{7}',
     'possibleNumberPattern' => '\\d{11}',
     'exampleNumber' => '18001234567',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '19(?:0[01]|4[78])\\d{7}',
     'possibleNumberPattern' => '\\d{11}',
     'exampleNumber' => '19001234567',
  )),
   'sharedCost' => NULL,
   'personalNumber' => NULL,
   'voip' => NULL,
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:1[29]|23|32|56)',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '112',
  )),
));