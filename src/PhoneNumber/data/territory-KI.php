<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'KI',
   'countryCode' => '686',
   'mainCountryForCode' => false,
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => NULL,
   'internationalPrefix' => '00',
   'nationalPrefix' => NULL,
   'nationalPrefixForParsing' => '0',
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
     'nationalNumberPattern' => '[2-689]\\d{4}',
     'possibleNumberPattern' => '\\d{5}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:[234]\\d|50|8[1-5])\\d{3}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '31234',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '6\\d{4}|9(?:[0-8]\\d|9[015-8])\\d{2}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '61234',
  )),
   'pager' => NULL,
   'tollFree' => NULL,
   'premiumRate' => NULL,
   'sharedCost' => NULL,
   'personalNumber' => NULL,
   'voip' => NULL,
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '10(?:[0-8]|5[01259])',
     'possibleNumberPattern' => '\\d{3,4}',
     'exampleNumber' => '100',
  )),
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '99[2349]',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '999',
  )),
));