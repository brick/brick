<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'MV',
   'countryCode' => '960',
   'mainCountryForCode' => false,
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => '00',
   'internationalPrefix' => '0(?:0|19)',
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
       'pattern' => '(\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '[3467]|9(?:[1-9]|0[1-9])',
      ),
       'format' => '$1-$2',
       'intlFormat' => 
      array (
      ),
    )),
    1 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{3})(\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '900',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[3467]\\d{6}|9(?:00\\d{7}|\\d{6})',
     'possibleNumberPattern' => '\\d{7,10}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:3(?:0[01]|3[0-59])|6(?:[567][02468]|8[024689]|90))\\d{4}',
     'possibleNumberPattern' => '\\d{7}',
     'exampleNumber' => '6701234',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:46[46]|7[3-9]\\d|9[6-9]\\d)\\d{4}',
     'possibleNumberPattern' => '\\d{7}',
     'exampleNumber' => '7712345',
  )),
   'pager' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '781\\d{4}',
     'possibleNumberPattern' => '\\d{7}',
     'exampleNumber' => '7812345',
  )),
   'tollFree' => NULL,
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '900\\d{7}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '9001234567',
  )),
   'sharedCost' => NULL,
   'personalNumber' => NULL,
   'voip' => NULL,
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:[19]0|23)',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '123',
  )),
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:02|19)',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '102',
  )),
));