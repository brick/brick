<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'CY',
   'countryCode' => '357',
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
    0 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{2})(\\d{6})',
       'leadingDigits' => 
      array (
      ),
       'format' => '$1 $2',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[257-9]\\d{7}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '2[2-6]\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '22345678',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '9[5-79]\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '96123456',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '800\\d{5}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '80001234',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '90[09]\\d{5}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '90012345',
  )),
   'sharedCost' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '80[1-9]\\d{5}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '80112345',
  )),
   'personalNumber' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '700\\d{5}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '70012345',
  )),
   'voip' => NULL,
   'uan' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:50|77)\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '77123456',
  )),
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:12|99)',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '112',
  )),
));