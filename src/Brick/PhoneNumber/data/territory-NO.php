<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'NO',
   'countryCode' => '47',
   'mainCountryForCode' => 'true',
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => NULL,
   'internationalPrefix' => '00',
   'nationalPrefix' => NULL,
   'nationalPrefixForParsing' => NULL,
   'nationalPrefixTransformRule' => NULL,
   'preferredExtnPrefix' => NULL,
   'nationalPrefixFormattingRule' => NULL,
   'nationalPrefixOptionalWhenFormatting' => false,
   'leadingZeroPossible' => 'true',
   'carrierCodeFormattingRule' => NULL,
   'availableFormats' => 
  array (
    0 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '([489]\\d{2})(\\d{2})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '[489]',
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
       'pattern' => '([235-7]\\d)(\\d{2})(\\d{2})(\\d{2})',
       'leadingDigits' => 
      array (
        0 => '[235-7]',
      ),
       'format' => '$1 $2 $3 $4',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '0\\d{4}|[2-9]\\d{7}',
     'possibleNumberPattern' => '\\d{5}(?:\\d{3})?',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:2[1-4]|3[1-3578]|5[1-35-7]|6[1-4679]|7[0-8])\\d{6}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '21234567',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:4[015-8]|5[89]|9\\d)\\d{6}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '41234567',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '80[01]\\d{5}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '80012345',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '82[09]\\d{5}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '82012345',
  )),
   'sharedCost' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '810(?:0[0-6]|[2-8]\\d)\\d{3}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '81021234',
  )),
   'personalNumber' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '880\\d{5}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '88012345',
  )),
   'voip' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '85[0-5]\\d{5}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '85012345',
  )),
   'uan' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '0\\d{4}|81(?:0(?:0[7-9]|1\\d)|5\\d{2})\\d{3}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '01234',
  )),
   'voicemail' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '81[23]\\d{5}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '81212345',
  )),
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '11[023]',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '112',
  )),
));