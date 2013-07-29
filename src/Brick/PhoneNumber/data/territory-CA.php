<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'CA',
   'countryCode' => '1',
   'mainCountryForCode' => false,
   'leadingDigits' => NULL,
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
     'nationalNumberPattern' => '[2-9]\\d{9}|3\\d{6}',
     'possibleNumberPattern' => '\\d{7}(?:\\d{3})?',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:2(?:04|[23]6|[48]9|50)|3(?:06|43|65)|4(?:03|1[68]|3[178]|5[06])|5(?:0[06]|1[49]|79|8[17])|6(?:0[04]|13|39|47)|7(?:0[059]|80|78)|8(?:[06]7|19|73)|90[25])[2-9]\\d{6}|310\\d{4}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '2042345678',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:2(?:04|[23]6|[48]9|50)|3(?:06|43|65)|4(?:03|1[68]|3[178]|5[06])|5(?:0[06]|1[49]|79|8[17])|6(?:0[04]|13|39|47)|7(?:0[059]|80|78)|8(?:[06]7|19|73)|90[25])[2-9]\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '2042345678',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '8(?:00|55|66|77|88)[2-9]\\d{6}|310\\d{4}',
     'possibleNumberPattern' => NULL,
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
     'nationalNumberPattern' => '112|911',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '911',
  )),
));