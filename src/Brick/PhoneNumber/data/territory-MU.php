<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'MU',
   'countryCode' => '230',
   'mainCountryForCode' => false,
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => '020',
   'internationalPrefix' => '0(?:[2-7]0|33)',
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
       'pattern' => '([2-9]\\d{2})(\\d{4})',
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
     'nationalNumberPattern' => '[2-9]\\d{6}',
     'possibleNumberPattern' => '\\d{7}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:2(?:[034789]\\d|1[0-7]|6[1-69])|4(?:[013-8]\\d|2[4-7])|[56]\\d{2}|8(?:14|3[129]))\\d{4}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '2012345',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:25\\d|4(?:2[12389]|9\\d)|7\\d{2}|8(?:20|7[15-8])|9[1-8]\\d)\\d{4}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '2512345',
  )),
   'pager' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '2(?:1[89]|2\\d)\\d{4}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '2181234',
  )),
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '80[012]\\d{4}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '8001234',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '30\\d{5}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '3012345',
  )),
   'sharedCost' => NULL,
   'personalNumber' => NULL,
   'voip' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '3(?:20|9\\d)\\d{4}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '3201234',
  )),
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:1[0-36-9]|[02-9]\\d|\\d{3,4})|8\\d{3}',
     'possibleNumberPattern' => '\\d{3,5}',
     'exampleNumber' => '995',
  )),
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '11[45]|99\\d',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '999',
  )),
));