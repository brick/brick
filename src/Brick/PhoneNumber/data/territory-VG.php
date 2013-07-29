<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'VG',
   'countryCode' => '1',
   'mainCountryForCode' => false,
   'leadingDigits' => '284',
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
     'nationalNumberPattern' => '[2589]\\d{9}',
     'possibleNumberPattern' => '\\d{7}(?:\\d{3})?',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '284(?:(?:229|4(?:22|9[45])|774|8(?:52|6[459]))\\d{4}|496[0-5]\\d{3})',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '2842291234',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '284(?:(?:3(?:0[0-3]|4[0-367])|4(?:4[0-6]|68|99)|54[0-57])\\d{4}|496[6-9]\\d{3})',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '2843001234',
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
   'voip' => NULL,
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