<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'RU',
   'countryCode' => '7',
   'mainCountryForCode' => 'true',
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => '8~10',
   'internationalPrefix' => '810',
   'nationalPrefix' => '8',
   'nationalPrefixForParsing' => NULL,
   'nationalPrefixTransformRule' => NULL,
   'preferredExtnPrefix' => NULL,
   'nationalPrefixFormattingRule' => '$NP ($FG)',
   'nationalPrefixOptionalWhenFormatting' => 'true',
   'leadingZeroPossible' => false,
   'carrierCodeFormattingRule' => NULL,
   'availableFormats' => 
  array (
    0 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{3})(\\d{2})(\\d{2})',
       'leadingDigits' => 
      array (
        0 => '[1-79]',
      ),
       'format' => '$1-$2-$3',
       'intlFormat' => 
      array (
        0 => 'NA',
      ),
    )),
    1 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '([3489]\\d{2})(\\d{3})(\\d{2})(\\d{2})',
       'leadingDigits' => 
      array (
        0 => '[34689]',
      ),
       'format' => '$1 $2-$3-$4',
       'intlFormat' => 
      array (
      ),
    )),
    2 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(7\\d{2})(\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '7',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[3489]\\d{9}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:3(?:0[12]|4[1-35-79]|5[1-3]|8[1-58]|9[0145])|4(?:01|1[1356]|2[13467]|7[1-5]|8[1-7]|9[1-689])|8(?:1[1-8]|2[01]|3[13-6]|4[0-8]|5[15]|6[1-35-7]|7[1-37-9]))\\d{7}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '3011234567',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '9\\d{9}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '9123456789',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '80[04]\\d{7}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '8001234567',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '80[39]\\d{7}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '8091234567',
  )),
   'sharedCost' => NULL,
   'personalNumber' => NULL,
   'voip' => NULL,
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '0[123]|112',
     'possibleNumberPattern' => '\\d{2,3}',
     'exampleNumber' => '112',
  )),
));