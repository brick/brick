<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'KH',
   'countryCode' => '855',
   'mainCountryForCode' => false,
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => NULL,
   'internationalPrefix' => '00[14-9]',
   'nationalPrefix' => '0',
   'nationalPrefixForParsing' => NULL,
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
       'nationalPrefixFormattingRule' => '$NP$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{2})(\\d{3})(\\d{3,4})',
       'leadingDigits' => 
      array (
        0 => '1\\d[1-9]|[2-9]',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
    1 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(1[89]00)(\\d{3})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '1[89]0',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[1-9]\\d{7,9}',
     'possibleNumberPattern' => '\\d{6,10}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:2[3-6]|3[2-6]|4[2-4]|[567][2-5])(?:[2-47-9]|5\\d|6\\d?)\\d{5}',
     'possibleNumberPattern' => '\\d{6,9}',
     'exampleNumber' => '23456789',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:(?:1\\d|6[06-9]|7(?:[07-9]|6\\d))[1-9]|8(?:0[89]|[134679]\\d|5[2-689]|8\\d{2})|9(?:[0-589][1-9]|[67][1-9]\\d?))\\d{5}',
     'possibleNumberPattern' => '\\d{8,9}',
     'exampleNumber' => '91234567',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1800(?:1\\d|2[019])\\d{4}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '1800123456',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1900(?:1\\d|2[09])\\d{4}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '1900123456',
  )),
   'sharedCost' => NULL,
   'personalNumber' => NULL,
   'voip' => NULL,
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '11[789]|666',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '117',
  )),
));