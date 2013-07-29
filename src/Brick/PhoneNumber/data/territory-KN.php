<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'KN',
   'countryCode' => '1',
   'mainCountryForCode' => false,
   'leadingDigits' => '869',
   'preferredInternationalPrefix' => NULL,
   'internationalPrefix' => '011',
   'nationalPrefix' => '1',
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
     'nationalNumberPattern' => '[589]\\d{9}',
     'possibleNumberPattern' => '\\d{7}(?:\\d{3})?',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '869(?:2(?:29|36)|302|4(?:6[5-9]|70))\\d{4}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '8692361234',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '869(?:5(?:5[6-8]|6[5-7])|66\\d|76[02-6])\\d{4}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '8695561234',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '8(?:00|55|66|77|88)[2-9]\\d{6}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '8002123456',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '900[2-9]\\d{6}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '9002123456',
  )),
   'sharedCost' => NULL,
   'personalNumber' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '5(?:00|33|44)[2-9]\\d{6}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '5002345678',
  )),
   'voip' => NULL,
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '333|9(?:11|99)',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '999',
  )),
));