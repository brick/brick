<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'CL',
   'countryCode' => '56',
   'mainCountryForCode' => false,
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => NULL,
   'internationalPrefix' => '(?:0|1(?:1[0-69]|2[0-57]|5[13-58]|69|7[0167]|8[018]))0',
   'nationalPrefix' => '0',
   'nationalPrefixForParsing' => '0|(1(?:1[0-69]|2[0-57]|5[13-58]|69|7[0167]|8[018]))',
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
       'nationalPrefixFormattingRule' => '($FG)',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => '$CC ($FG)',
       'pattern' => '(2)(\\d{3,4})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '2',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
    1 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '($FG)',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => '$CC ($FG)',
       'pattern' => '(\\d{2})(\\d{2,3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '[357]|4[1-35]|6[13-57]',
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
       'pattern' => '(9)([5-9]\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '9',
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
       'pattern' => '(44)(\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '44',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
    4 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '([68]00)(\\d{3})(\\d{3,4})',
       'leadingDigits' => 
      array (
        0 => '60|8',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
    5 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(600)(\\d{3})(\\d{2})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '60',
      ),
       'format' => '$1 $2 $3 $4',
       'intlFormat' => 
      array (
      ),
    )),
    6 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(1230)(\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '1',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:[2-9]|600|123)\\d{7,8}',
     'possibleNumberPattern' => '\\d{6,11}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '600\\d{7,8}',
     'possibleNumberPattern' => '\\d{10,11}',
     'exampleNumber' => '6001234567',
  )),
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:[23]2|41|58)\\d{7}|(?:3[3-5]|4[235]|5[1-357]|6[13-57]|7[1-35])\\d{6,7}',
     'possibleNumberPattern' => '\\d{6,9}',
     'exampleNumber' => '221234567',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '9[5-9]\\d{7}',
     'possibleNumberPattern' => '\\d{8,9}',
     'exampleNumber' => '961234567',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '800\\d{6}|1230\\d{7}',
     'possibleNumberPattern' => '\\d{9,11}',
     'exampleNumber' => '800123456',
  )),
   'premiumRate' => NULL,
   'sharedCost' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '600\\d{7,8}',
     'possibleNumberPattern' => '\\d{10,11}',
     'exampleNumber' => '6001234567',
  )),
   'personalNumber' => NULL,
   'voip' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '44\\d{7}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '441234567',
  )),
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '13[123]',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '133',
  )),
));