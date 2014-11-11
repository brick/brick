<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'SG',
   'countryCode' => '65',
   'mainCountryForCode' => false,
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => NULL,
   'internationalPrefix' => '0[0-3]\\d',
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
       'pattern' => '([3689]\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '[369]|8[1-9]',
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
       'pattern' => '(1[89]00)(\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '1[89]',
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
       'pattern' => '(7000)(\\d{4})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '70',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
    3 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(800)(\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '80',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[36]\\d{7}|[17-9]\\d{7,10}',
     'possibleNumberPattern' => '\\d{8,11}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '6[1-9]\\d{6}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '61234567',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:8[1-7]|9[0-8])\\d{6}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '81234567',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1?800\\d{7}',
     'possibleNumberPattern' => '\\d{10,11}',
     'exampleNumber' => '18001234567',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1900\\d{7}',
     'possibleNumberPattern' => '\\d{11}',
     'exampleNumber' => '19001234567',
  )),
   'sharedCost' => NULL,
   'personalNumber' => NULL,
   'voip' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '3[12]\\d{6}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '31234567',
  )),
   'uan' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '7000\\d{7}',
     'possibleNumberPattern' => '\\d{11}',
     'exampleNumber' => '70001234567',
  )),
   'voicemail' => NULL,
   'shortCode' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:[0136]\\d{2}|[89](?:[1-9]\\d|0[1-9])|[57]\\d{2,3})|99[0246-8]',
     'possibleNumberPattern' => '\\d{3,5}',
     'exampleNumber' => '1312',
  )),
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '99[359]',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '999',
  )),
));