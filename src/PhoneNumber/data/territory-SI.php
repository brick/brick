<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'SI',
   'countryCode' => '386',
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
       'nationalPrefixFormattingRule' => '($NP$FG)',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d)(\\d{3})(\\d{2})(\\d{2})',
       'leadingDigits' => 
      array (
        0 => '[12]|3[4-8]|4[24-8]|5[2-8]|7[3-8]',
      ),
       'format' => '$1 $2 $3 $4',
       'intlFormat' => 
      array (
      ),
    )),
    1 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '([3-7]\\d)(\\d{3})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '[37][01]|4[019]|51|6',
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
       'pattern' => '([89][09])(\\d{3,6})',
       'leadingDigits' => 
      array (
        0 => '[89][09]',
      ),
       'format' => '$1 $2',
       'intlFormat' => 
      array (
      ),
    )),
    3 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '([58]\\d{2})(\\d{5})',
       'leadingDigits' => 
      array (
        0 => '59|8[1-3]',
      ),
       'format' => '$1 $2',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[1-7]\\d{6,7}|[89]\\d{4,7}',
     'possibleNumberPattern' => '\\d{5,8}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:1\\d|[25][2-8]|3[4-8]|4[24-8]|7[3-8])\\d{6}',
     'possibleNumberPattern' => '\\d{7,8}',
     'exampleNumber' => '11234567',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:[37][01]|4[019]|51|6[48])\\d{6}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '31234567',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '80\\d{4,6}',
     'possibleNumberPattern' => '\\d{6,8}',
     'exampleNumber' => '80123456',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '90\\d{4,6}|89[1-3]\\d{2,5}',
     'possibleNumberPattern' => '\\d{5,8}',
     'exampleNumber' => '90123456',
  )),
   'sharedCost' => NULL,
   'personalNumber' => NULL,
   'voip' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:59|8[1-3])\\d{6}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '59012345',
  )),
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '11[23]',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '112',
  )),
));