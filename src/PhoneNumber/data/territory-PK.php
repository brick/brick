<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'PK',
   'countryCode' => '92',
   'mainCountryForCode' => false,
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => NULL,
   'internationalPrefix' => '00',
   'nationalPrefix' => '0',
   'nationalPrefixForParsing' => NULL,
   'nationalPrefixTransformRule' => NULL,
   'preferredExtnPrefix' => NULL,
   'nationalPrefixFormattingRule' => '($NP$FG)',
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
       'pattern' => '(\\d{2})(111)(\\d{3})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '(?:2[125]|4[0-246-9]|5[1-35-7]|6[1-8]|7[14]|8[16]|91)1',
        1 => '(?:2[125]|4[0-246-9]|5[1-35-7]|6[1-8]|7[14]|8[16]|91)11',
        2 => '(?:2[125]|4[0-246-9]|5[1-35-7]|6[1-8]|7[14]|8[16]|91)111',
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
       'pattern' => '(\\d{3})(111)(\\d{3})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '2[349]|45|54|60|72|8[2-5]|9[2-9]',
        1 => '(?:2[349]|45|54|60|72|8[2-5]|9[2-9])\\d1',
        2 => '(?:2[349]|45|54|60|72|8[2-5]|9[2-9])\\d11',
        3 => '(?:2[349]|45|54|60|72|8[2-5]|9[2-9])\\d111',
      ),
       'format' => '$1 $2 $3 $4',
       'intlFormat' => 
      array (
      ),
    )),
    2 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{2})(\\d{7,8})',
       'leadingDigits' => 
      array (
        0 => '(?:2[125]|4[0-246-9]|5[1-35-7]|6[1-8]|7[14]|8[16]|91)[2-9]',
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
       'pattern' => '(\\d{3})(\\d{6,7})',
       'leadingDigits' => 
      array (
        0 => '2[349]|45|54|60|72|8[2-5]|9[2-9]',
        1 => '(?:2[349]|45|54|60|72|8[2-5]|9[2-9])\\d[2-9]',
      ),
       'format' => '$1 $2',
       'intlFormat' => 
      array (
      ),
    )),
    4 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$NP$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(3\\d{2})(\\d{7})',
       'leadingDigits' => 
      array (
        0 => '3',
      ),
       'format' => '$1 $2',
       'intlFormat' => 
      array (
      ),
    )),
    5 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '([15]\\d{3})(\\d{5,6})',
       'leadingDigits' => 
      array (
        0 => '58[12]|1',
      ),
       'format' => '$1 $2',
       'intlFormat' => 
      array (
      ),
    )),
    6 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(586\\d{2})(\\d{5})',
       'leadingDigits' => 
      array (
        0 => '586',
      ),
       'format' => '$1 $2',
       'intlFormat' => 
      array (
      ),
    )),
    7 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$NP$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '([89]00)(\\d{3})(\\d{2})',
       'leadingDigits' => 
      array (
        0 => '[89]00',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1\\d{8}|[2-8]\\d{5,11}|9(?:[013-9]\\d{4,9}|2\\d(?:111\\d{6}|\\d{3,7}))',
     'possibleNumberPattern' => '\\d{6,12}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:21|42)[2-9]\\d{7}|(?:2[25]|4[0146-9]|5[1-35-7]|6[1-8]|7[14]|8[16]|91)[2-9]\\d{6}|(?:2(?:3[2358]|4[2-4]|9[2-8])|45[3479]|54[2-467]|60[468]|72[236]|8(?:2[2-689]|3[23578]|4[3478]|5[2356])|9(?:1|2[2-8]|3[27-9]|4[2-6]|6[3569]|9[25-8]))[2-9]\\d{5,6}|58[126]\\d{7}',
     'possibleNumberPattern' => '\\d{6,10}',
     'exampleNumber' => '2123456789',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '3(?:0\\d|1[1-5]|2[0-5]|3[1-6]|4[1-7]|55|64)\\d{7}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '3012345678',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '800\\d{5}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '80012345',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '900\\d{5}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '90012345',
  )),
   'sharedCost' => NULL,
   'personalNumber' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '122\\d{6}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '122044444',
  )),
   'voip' => NULL,
   'uan' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:2(?:[125]|3[2358]|4[2-4]|9[2-8])|4(?:[0-246-9]|5[3479])|5(?:[1-35-7]|4[2-467])|6(?:[1-8]|0[468])|7(?:[14]|2[236])|8(?:[16]|2[2-689]|3[23578]|4[3478]|5[2356])|9(?:1|22|3[27-9]|4[2-6]|6[3569]|9[2-7]))111\\d{6}',
     'possibleNumberPattern' => '\\d{11,12}',
     'exampleNumber' => '21111825888',
  )),
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:1(?:22?|5)|[56])',
     'possibleNumberPattern' => '\\d{2,4}',
     'exampleNumber' => '112',
  )),
));