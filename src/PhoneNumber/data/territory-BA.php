<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'BA',
   'countryCode' => '387',
   'mainCountryForCode' => false,
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => NULL,
   'internationalPrefix' => '00',
   'nationalPrefix' => '0',
   'nationalPrefixForParsing' => NULL,
   'nationalPrefixTransformRule' => NULL,
   'preferredExtnPrefix' => NULL,
   'nationalPrefixFormattingRule' => '$NP$FG',
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
       'pattern' => '(\\d{2})(\\d{3})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '[3-5]',
      ),
       'format' => '$1 $2-$3',
       'intlFormat' => 
      array (
      ),
    )),
    1 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{2})(\\d{3})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '6[1-356]|[7-9]',
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
       'pattern' => '(\\d{2})(\\d{2})(\\d{2})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '6[047]',
      ),
       'format' => '$1 $2 $3 $4',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[3-9]\\d{7,8}',
     'possibleNumberPattern' => '\\d{6,9}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:[35]\\d|49)\\d{6}',
     'possibleNumberPattern' => '\\d{6,8}',
     'exampleNumber' => '30123456',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '6(?:03|44|71|[1-356])\\d{6}',
     'possibleNumberPattern' => '\\d{8,9}',
     'exampleNumber' => '61123456',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '8[08]\\d{6}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '80123456',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '9[0246]\\d{6}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '90123456',
  )),
   'sharedCost' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '8[12]\\d{6}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '82123456',
  )),
   'personalNumber' => NULL,
   'voip' => NULL,
   'uan' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '70[23]\\d{5}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '70223456',
  )),
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '12[234]',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '122',
  )),
));