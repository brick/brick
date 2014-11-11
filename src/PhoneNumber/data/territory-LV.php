<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'LV',
   'countryCode' => '371',
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
       'pattern' => '([2689]\\d)(\\d{3})(\\d{3})',
       'leadingDigits' => 
      array (
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[2689]\\d{7}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '6[3-8]\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '63123456',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '2\\d{7}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '21234567',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '80\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '80123456',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '90\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '90123456',
  )),
   'sharedCost' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '81\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '81123456',
  )),
   'personalNumber' => NULL,
   'voip' => NULL,
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '0[123]|112',
     'possibleNumberPattern' => '\\d{2,3}',
     'exampleNumber' => '112',
  )),
));