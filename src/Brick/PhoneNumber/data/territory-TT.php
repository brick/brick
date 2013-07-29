<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'TT',
   'countryCode' => '1',
   'mainCountryForCode' => false,
   'leadingDigits' => '868',
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
     'nationalNumberPattern' => '868(?:2(?:01|2[1-5])|6(?:07|1[4-6]|2[1-9]|[3-6]\\d|7[0-79]|9[0-8])|82[12])\\d{4}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '8682211234',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '868(?:2(?:8[59]|9\\d)|3(?:0[1-9]|1[02-9]|[2-9]\\d)|4(?:[679]\\d|8[0-4])|6(?:20|78|8\\d)|7(?:03|1[02-9]|[2-9]\\d))\\d{4}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '8682911234',
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
     'nationalNumberPattern' => '99[09]',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '999',
  )),
));