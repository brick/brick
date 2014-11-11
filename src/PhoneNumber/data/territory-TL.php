<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'TL',
   'countryCode' => '670',
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
       'pattern' => '(\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '[2-489]',
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
       'pattern' => '(\\d{4})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '7',
      ),
       'format' => '$1 $2',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[2-489]\\d{6}|7\\d{6,7}',
     'possibleNumberPattern' => '\\d{7,8}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:2[1-5]|3[1-9]|4[1-4])\\d{5}',
     'possibleNumberPattern' => '\\d{7}',
     'exampleNumber' => '2112345',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '7[78]\\d{6}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '77212345',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '80\\d{5}',
     'possibleNumberPattern' => '\\d{7}',
     'exampleNumber' => '8012345',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '90\\d{5}',
     'possibleNumberPattern' => '\\d{7}',
     'exampleNumber' => '9012345',
  )),
   'sharedCost' => NULL,
   'personalNumber' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '70\\d{5}',
     'possibleNumberPattern' => '\\d{7}',
     'exampleNumber' => '7012345',
  )),
   'voip' => NULL,
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:0[02]|2[0138]|72|9[07])',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '102',
  )),
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '11[25]',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '112',
  )),
));