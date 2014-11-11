<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'JE',
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
     'nationalNumberPattern' => '1534[2-9]\\d{5}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '1534250123',
  )),
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1534\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '1534456789',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '7(?:509|7(?:00|97)|829|937)\\d{6}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '7797123456',
  )),
   'pager' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '76(?:0[012]|2[356]|4[0134]|5[49]|6[0-369]|77|81|9[39])\\d{6}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '7640123456',
  )),
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '80(?:07(?:35|81)|8901)\\d{4}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '8007354567',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:871206|90(?:066[59]|1810|71(?:07|55)))\\d{4}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '9018105678',
  )),
   'sharedCost' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '8(?:4(?:4(?:4(?:05|42|69)|703)|5(?:041|800))|70002)\\d{4}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '8447034567',
  )),
   'personalNumber' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '701511\\d{4}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '7015115678',
  )),
   'voip' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '56\\d{8}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '5612345678',
  )),
   'uan' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '3(?:0(?:07(?:35|81)|8901)|3\\d{4}|4(?:4(?:4(?:05|42|69)|703)|5(?:041|800))|7(?:0002|1206))\\d{4}|55\\d{8}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '5512345678',
  )),
   'voicemail' => NULL,
   'shortCode' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:00|18\\d{3}|23|4(?:[14]|28|7\\d)|5\\d|7(?:0[12]|[128]|35?)|808|9[135])|23[234]',
     'possibleNumberPattern' => '\\d{3,6}',
     'exampleNumber' => '150',
  )),
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '112|999',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '999',
  )),
));