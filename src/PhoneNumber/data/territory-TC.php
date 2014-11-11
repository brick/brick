<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'TC',
   'countryCode' => '1',
   'mainCountryForCode' => false,
   'leadingDigits' => '649',
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
     'nationalNumberPattern' => '[5689]\\d{9}',
     'possibleNumberPattern' => '\\d{7}(?:\\d{3})?',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '649(?:712|9(?:4\\d|50))\\d{4}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '6497121234',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '649(?:2(?:3[129]|4[1-7])|3(?:3[1-39]|4[1-7])|4[34][12])\\d{4}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '6492311234',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '8(?:00|55|66|77|88)[2-9]\\d{6}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '8002345678',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '900[2-9]\\d{6}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '9002345678',
  )),
   'sharedCost' => NULL,
   'personalNumber' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '5(?:00|33|44)[2-9]\\d{6}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '5002345678',
  )),
   'voip' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '64971[01]\\d{4}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '6497101234',
  )),
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '9(?:11|99)',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '911',
  )),
));