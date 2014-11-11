<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'IM',
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
     'nationalNumberPattern' => '1624[2-9]\\d{5}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '1624250123',
  )),
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1624\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '1624456789',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '7[569]24\\d{6}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '7924123456',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '808162\\d{4}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '8081624567',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:872299|90[0167]624)\\d{4}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '9016247890',
  )),
   'sharedCost' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '8(?:4(?:40[49]06|5624\\d)|70624\\d)\\d{3}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '8456247890',
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
     'nationalNumberPattern' => '3(?:08162\\d|3\\d{5}|4(?:40[49]06|5624\\d)|7(?:0624\\d|2299\\d))\\d{3}|55\\d{8}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '5512345678',
  )),
   'voicemail' => NULL,
   'shortCode' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1\\d{2}(?:\\d{3})?',
     'possibleNumberPattern' => '\\d{3,6}',
     'exampleNumber' => '150',
  )),
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '999',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '999',
  )),
));