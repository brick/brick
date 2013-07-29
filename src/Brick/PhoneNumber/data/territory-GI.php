<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'GI',
   'countryCode' => '350',
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
     'nationalNumberPattern' => '[2568]\\d{7}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '2(?:00\\d|16[0-7]|22[2457])\\d{4}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '20012345',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:5[4-8]|60)\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '57123456',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '80\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '80123456',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '8[1-689]\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '88123456',
  )),
   'sharedCost' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '87\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '87123456',
  )),
   'personalNumber' => NULL,
   'voip' => NULL,
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:00|1(?:6(?:00[06]|11[17])|8\\d{2})|23|4(?:1|7[014])|5[015]|9[34])|8(?:00|4[0-2]|8\\d)',
     'possibleNumberPattern' => '\\d{3,6}',
     'exampleNumber' => '116123',
  )),
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:12|9[09])',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '112',
  )),
));