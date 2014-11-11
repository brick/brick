<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'SH',
   'countryCode' => '290',
   'mainCountryForCode' => false,
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => NULL,
   'internationalPrefix' => '00',
   'nationalPrefix' => NULL,
   'nationalPrefixForParsing' => NULL,
   'nationalPrefixTransformRule' => NULL,
   'preferredExtnPrefix' => NULL,
   'nationalPrefixFormattingRule' => NULL,
   'nationalPrefixOptionalWhenFormatting' => false,
   'leadingZeroPossible' => false,
   'carrierCodeFormattingRule' => NULL,
   'availableFormats' => 
  array (
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[2-9]\\d{3}',
     'possibleNumberPattern' => '\\d{4}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:[2-468]\\d|7[01])\\d{2}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '2158',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => 'NA',
     'possibleNumberPattern' => 'NA',
     'exampleNumber' => NULL,
  )),
   'pager' => NULL,
   'tollFree' => NULL,
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:[59]\\d|7[2-9])\\d{2}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '5012',
  )),
   'sharedCost' => NULL,
   'personalNumber' => NULL,
   'voip' => NULL,
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1\\d{2,3}',
     'possibleNumberPattern' => '\\d{3,4}',
     'exampleNumber' => '1234',
  )),
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '9(?:11|99)',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '999',
  )),
));