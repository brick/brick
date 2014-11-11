<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'SC',
   'countryCode' => '248',
   'mainCountryForCode' => false,
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => '00',
   'internationalPrefix' => '0[0-2]',
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
       'pattern' => '(\\d{3})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '[89]',
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
       'pattern' => '(\\d)(\\d{3})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '[246]',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[24689]\\d{5,6}',
     'possibleNumberPattern' => '\\d{6,7}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '4[2-46]\\d{5}',
     'possibleNumberPattern' => '\\d{7}',
     'exampleNumber' => '4217123',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '2[5-8]\\d{5}',
     'possibleNumberPattern' => '\\d{7}',
     'exampleNumber' => '2510123',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '8000\\d{2}',
     'possibleNumberPattern' => '\\d{6}',
     'exampleNumber' => '800000',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '98\\d{4}',
     'possibleNumberPattern' => '\\d{6}',
     'exampleNumber' => '981234',
  )),
   'sharedCost' => NULL,
   'personalNumber' => NULL,
   'voip' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '64\\d{5}',
     'possibleNumberPattern' => '\\d{7}',
     'exampleNumber' => '6412345',
  )),
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:0\\d|1[027]|2[0-8]|3[13]|4[0-2]|[59][15]|6[1-9]|7[124-6]|8[158])|96\\d{2}',
     'possibleNumberPattern' => '\\d{3,4}',
     'exampleNumber' => '100',
  )),
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '999',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '999',
  )),
));