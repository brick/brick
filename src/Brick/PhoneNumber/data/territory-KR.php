<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'KR',
   'countryCode' => '82',
   'mainCountryForCode' => false,
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => NULL,
   'internationalPrefix' => '00(?:[124-68]|[37]\\d{2})',
   'nationalPrefix' => '0',
   'nationalPrefixForParsing' => '0(8[1-46-8]|85\\d{2})?',
   'nationalPrefixTransformRule' => NULL,
   'preferredExtnPrefix' => NULL,
   'nationalPrefixFormattingRule' => '$NP$FG',
   'nationalPrefixOptionalWhenFormatting' => false,
   'leadingZeroPossible' => false,
   'carrierCodeFormattingRule' => '$NP$CC-$FG',
   'availableFormats' => 
  array (
    0 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{2})(\\d{4})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '1(?:0|1[19]|[69]9|5[458])|[57]0',
        1 => '1(?:0|1[19]|[69]9|5(?:44|59|8))|[57]0',
      ),
       'format' => '$1-$2-$3',
       'intlFormat' => 
      array (
      ),
    )),
    1 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{2})(\\d{3,4})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '1(?:[169][2-8]|[78]|5[1-4])|[68]0|[3-6][1-9][2-9]',
        1 => '1(?:[169][2-8]|[78]|5(?:[1-3]|4[56]))|[68]0|[3-6][1-9][2-9]',
      ),
       'format' => '$1-$2-$3',
       'intlFormat' => 
      array (
      ),
    )),
    2 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{3})(\\d)(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '131',
        1 => '1312',
      ),
       'format' => '$1-$2-$3',
       'intlFormat' => 
      array (
      ),
    )),
    3 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{3})(\\d{2})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '131',
        1 => '131[13-9]',
      ),
       'format' => '$1-$2-$3',
       'intlFormat' => 
      array (
      ),
    )),
    4 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{3})(\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '13[2-9]',
      ),
       'format' => '$1-$2-$3',
       'intlFormat' => 
      array (
      ),
    )),
    5 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{2})(\\d{2})(\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '30',
      ),
       'format' => '$1-$2-$3-$4',
       'intlFormat' => 
      array (
      ),
    )),
    6 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d)(\\d{3,4})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '2[2-9]',
      ),
       'format' => '$1-$2-$3',
       'intlFormat' => 
      array (
      ),
    )),
    7 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d)(\\d{3,4})',
       'leadingDigits' => 
      array (
        0 => '21[0-46-9]',
      ),
       'format' => '$1-$2',
       'intlFormat' => 
      array (
      ),
    )),
    8 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{2})(\\d{3,4})',
       'leadingDigits' => 
      array (
        0 => '[3-6][1-9]1',
        1 => '[3-6][1-9]1(?:[0-46-9])',
      ),
       'format' => '$1-$2',
       'intlFormat' => 
      array (
      ),
    )),
    9 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{4})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '1(?:5[46-9]|6[04678])',
        1 => '1(?:5(?:44|66|77|88|99)|6(?:00|44|6[16]|70|88))',
      ),
       'format' => '$1-$2',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[1-7]\\d{3,9}|8\\d{8}',
     'possibleNumberPattern' => '\\d{4,10}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:2|3[1-3]|[46][1-4]|5[1-5])(?:1\\d{2,3}|[2-9]\\d{6,7})',
     'possibleNumberPattern' => '\\d{4,10}',
     'exampleNumber' => '22123456',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1[0-26-9]\\d{7,8}',
     'possibleNumberPattern' => '\\d{9,10}',
     'exampleNumber' => '1023456789',
  )),
   'pager' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '15\\d{7,8}',
     'possibleNumberPattern' => '\\d{9,10}',
     'exampleNumber' => '1523456789',
  )),
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '80\\d{7}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '801234567',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '60[2-9]\\d{6}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '602345678',
  )),
   'sharedCost' => NULL,
   'personalNumber' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '50\\d{8}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '5012345678',
  )),
   'voip' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '70\\d{8}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '7012345678',
  )),
   'uan' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:5(?:44|66|77|88|99)|6(?:00|44|6[16]|70|88))\\d{4}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '15441234',
  )),
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '11[29]',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '112',
  )),
));