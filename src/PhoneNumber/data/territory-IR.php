<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'IR',
   'countryCode' => '98',
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
       'pattern' => '(21)(\\d{3,5})',
       'leadingDigits' => 
      array (
        0 => '21',
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
       'pattern' => '(21)(\\d{3})(\\d{3,4})',
       'leadingDigits' => 
      array (
        0 => '21',
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
       'pattern' => '(2[16])(\\d{4})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '2[16]',
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
       'pattern' => '(\\d{3})(\\d{3})(\\d{3,4})',
       'leadingDigits' => 
      array (
        0 => '[13-9]|2[02-9]',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[14-8]\\d{6,9}|[23]\\d{5,9}|9(?:[1-4]\\d{8}|9\\d{2,8})',
     'possibleNumberPattern' => '\\d{4,10}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:[145](?:1[1-9]|[2-9]\\d)\\d{0,3}|[23][1-9]\\d{0,4}|6[1-9]\\d{1,4}|[78]\\d{2,5})\\d{4}',
     'possibleNumberPattern' => '\\d{6,10}',
     'exampleNumber' => '2123456789',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '9[1-3]\\d{8}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '9123456789',
  )),
   'pager' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '943\\d{7}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '9432123456',
  )),
   'tollFree' => NULL,
   'premiumRate' => NULL,
   'sharedCost' => NULL,
   'personalNumber' => NULL,
   'voip' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:[2-6]0\\d|993)\\d{7}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '9932123456',
  )),
   'uan' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '9990\\d{0,6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '9990123456',
  )),
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:1[025]|25)',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '112',
  )),
));