<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'BQ',
   'countryCode' => '599',
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
     'nationalNumberPattern' => '[347]\\d{6}',
     'possibleNumberPattern' => '\\d{7}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:318[023]|416[0239]|7(?:1[578]|50)\\d)\\d{3}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '7151234',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:318[1456]|416[15-8]|7(?:0[01]|[89]\\d)\\d)\\d{3}|',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '3181234',
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
     'nationalNumberPattern' => '112|911',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '112',
  )),
));