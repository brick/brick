<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'GR',
   'countryCode' => '30',
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
       'pattern' => '([27]\\d)(\\d{4})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '21|7',
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
       'pattern' => '(\\d{3})(\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '2[2-9]1|[689]',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
    2 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(2\\d{3})(\\d{6})',
       'leadingDigits' => 
      array (
        0 => '2[2-9][02-9]',
      ),
       'format' => '$1 $2',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[26-9]\\d{9}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '2(?:1\\d{2}|2(?:3[1-8]|4[1-7]|5[1-4]|6[1-8]|7[1-5]|[289][1-9])|3(?:1\\d|2[1-57]|3[1-4]|[45][1-3]|7[1-7]|8[1-6]|9[1-79])|4(?:1\\d|2[1-8]|3[1-4]|4[13-5]|6[1-578]|9[1-5])|5(?:1\\d|[239][1-4]|4[124]|5[1-6])|6(?:1\\d|3[124]|4[1-7]|5[13-9]|[269][1-6]|7[14]|8[1-5])|7(?:1\\d|2[1-5]|3[1-6]|4[1-7]|5[1-57]|6[134]|9[15-7])|8(?:1\\d|2[1-5]|[34][1-4]|9[1-7]))\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '2123456789',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '69\\d{8}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '6912345678',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '800\\d{7}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '8001234567',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '90[19]\\d{7}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '9091234567',
  )),
   'sharedCost' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '8(?:0[16]|12|25)\\d{7}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '8011234567',
  )),
   'personalNumber' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '70\\d{8}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '7012345678',
  )),
   'voip' => NULL,
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:00|12|66|99)',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '112',
  )),
));