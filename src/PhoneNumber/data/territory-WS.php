<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'WS',
   'countryCode' => '685',
   'mainCountryForCode' => false,
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => NULL,
   'internationalPrefix' => '0',
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
       'pattern' => '(8\\d{2})(\\d{3,4})',
       'leadingDigits' => 
      array (
        0 => '8',
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
       'pattern' => '(7\\d)(\\d{5})',
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
     'nationalNumberPattern' => '[2-8]\\d{4,6}',
     'possibleNumberPattern' => '\\d{5,7}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:[2-5]\\d|6[1-9]|84\\d{2})\\d{3}',
     'possibleNumberPattern' => '\\d{5,7}',
     'exampleNumber' => '22123',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:60|7[25-7]\\d)\\d{4}',
     'possibleNumberPattern' => '\\d{6,7}',
     'exampleNumber' => '601234',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '800\\d{3}',
     'possibleNumberPattern' => '\\d{6}',
     'exampleNumber' => '800123',
  )),
   'premiumRate' => NULL,
   'sharedCost' => NULL,
   'personalNumber' => NULL,
   'voip' => NULL,
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '99[4-6]',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '994',
  )),
));