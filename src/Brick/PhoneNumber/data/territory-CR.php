<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'CR',
   'countryCode' => '506',
   'mainCountryForCode' => false,
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => NULL,
   'internationalPrefix' => '00',
   'nationalPrefix' => NULL,
   'nationalPrefixForParsing' => '(19(?:0[0-2468]|19|66|77))',
   'nationalPrefixTransformRule' => NULL,
   'preferredExtnPrefix' => NULL,
   'nationalPrefixFormattingRule' => NULL,
   'nationalPrefixOptionalWhenFormatting' => false,
   'leadingZeroPossible' => false,
   'carrierCodeFormattingRule' => '$CC $FG',
   'availableFormats' => 
  array (
    0 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{4})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '[24-7]|8[3-9]',
      ),
       'format' => '$1 $2',
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
        0 => '[89]0',
      ),
       'format' => '$1-$2-$3',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[24-9]\\d{7,9}',
     'possibleNumberPattern' => '\\d{8,10}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '2[24-7]\\d{6}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '22123456',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '5(?:0[0-4]|7[01])\\d{5}|[67][0-2]\\d{6}|8[3-9]\\d{6}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '83123456',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '800\\d{7}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '8001234567',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '90[059]\\d{7}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '9001234567',
  )),
   'sharedCost' => NULL,
   'personalNumber' => NULL,
   'voip' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '210[0-6]\\d{4}|4(?:0(?:[04]0\\d{4}|10[0-3]\\d{3}|2(?:00\\d|900)\\d{2}|3[01]\\d{4}|5\\d{5}|70[01]\\d{3})|1[01]\\d{5}|400\\d{4}|70[0-2]\\d{4})|5100\\d{4}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '40001234',
  )),
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:0(?:00|15|2[2-4679])|1(?:1[0-35-9]|37|[46]6|7[57]|8[79]|9[0-379])|2(?:00|[12]2|34|55)|333|400|5(?:15|5[15])|693|7(?:00|1[789]|2[02]|[67]7)|975)',
     'possibleNumberPattern' => '\\d{4}',
     'exampleNumber' => '1022',
  )),
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '112|911',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '911',
  )),
));