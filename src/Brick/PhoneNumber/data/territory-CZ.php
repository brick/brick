<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'CZ',
   'countryCode' => '420',
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
       'pattern' => '([2-9]\\d{2})(\\d{3})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '[2-8]|9[015-7]',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
    1 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(96\\d)(\\d{3})(\\d{3})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '96',
      ),
       'format' => '$1 $2 $3 $4',
       'intlFormat' => 
      array (
      ),
    )),
    2 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(9\\d)(\\d{3})(\\d{3})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '9[36]',
      ),
       'format' => '$1 $2 $3 $4',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[2-8]\\d{8}|9\\d{8,11}',
     'possibleNumberPattern' => '\\d{9,12}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '2\\d{8}|(?:3[1257-9]|4[16-9]|5[13-9])\\d{7}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '212345678',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:60[1-8]|7(?:0[2-5]|[2379]\\d))\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '601123456',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '800\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '800123456',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '9(?:0[05689]|76)\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '900123456',
  )),
   'sharedCost' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '8[134]\\d{7}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '811234567',
  )),
   'personalNumber' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '70[01]\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '700123456',
  )),
   'voip' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '9[17]0\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '910123456',
  )),
   'uan' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '9(?:5\\d|7[234])\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '972123456',
  )),
   'voicemail' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '9(?:3\\d{9}|6\\d{7,10})',
     'possibleNumberPattern' => '\\d{9,12}',
     'exampleNumber' => '93123456789',
  )),
   'shortCode' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:1(?:6\\d{3}|8\\d)|2\\d{2,3}|3\\d{3,4}|4\\d{3}|99)',
     'possibleNumberPattern' => '\\d{4,6}',
     'exampleNumber' => '116123',
  )),
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:12|5[058])',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '112',
  )),
));