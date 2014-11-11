<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'GG',
   'countryCode' => '44',
   'mainCountryForCode' => false,
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => NULL,
   'internationalPrefix' => '00',
   'nationalPrefix' => '0',
   'nationalPrefixForParsing' => NULL,
   'nationalPrefixTransformRule' => NULL,
   'preferredExtnPrefix' => ' x',
   'nationalPrefixFormattingRule' => '$NP$FG',
   'nationalPrefixOptionalWhenFormatting' => false,
   'leadingZeroPossible' => false,
   'carrierCodeFormattingRule' => NULL,
   'availableFormats' => 
  array (
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[135789]\\d{6,9}',
     'possibleNumberPattern' => '\\d{6,10}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1481[2-9]\\d{5}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '1481250123',
  )),
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1481\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '1481456789',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '7(?:781|839|911)\\d{6}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '7781123456',
  )),
   'pager' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '76(?:0[012]|2[356]|4[0134]|5[49]|6[0-369]|77|81|9[39])\\d{6}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '7640123456',
  )),
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '80(?:0(?:1111|\\d{6,7})|8\\d{7})|500\\d{6}',
     'possibleNumberPattern' => '\\d{7}(?:\\d{2,3})?',
     'exampleNumber' => '8001234567',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:87[123]|9(?:[01]\\d|8[0-3]))\\d{7}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '9012345678',
  )),
   'sharedCost' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '8(?:4(?:5464\\d|[2-5]\\d{7})|70\\d{7})',
     'possibleNumberPattern' => '\\d{7}(?:\\d{3})?',
     'exampleNumber' => '8431234567',
  )),
   'personalNumber' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '70\\d{8}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '7012345678',
  )),
   'voip' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '56\\d{8}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '5612345678',
  )),
   'uan' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:3[0347]|55)\\d{8}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '5512345678',
  )),
   'voicemail' => NULL,
   'shortCode' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:0[01]|1(?:1|[68]\\d{3})|23|4(?:1|7\\d)|55|800\\d|95)',
     'possibleNumberPattern' => '\\d{3,6}',
     'exampleNumber' => '155',
  )),
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '112|999',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '999',
  )),
));