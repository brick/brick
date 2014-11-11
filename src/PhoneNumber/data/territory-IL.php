<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'IL',
   'countryCode' => '972',
   'mainCountryForCode' => false,
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => NULL,
   'internationalPrefix' => '0(?:0|1[2-9])',
   'nationalPrefix' => '0',
   'nationalPrefixForParsing' => NULL,
   'nationalPrefixTransformRule' => NULL,
   'preferredExtnPrefix' => NULL,
   'nationalPrefixFormattingRule' => '$FG',
   'nationalPrefixOptionalWhenFormatting' => false,
   'leadingZeroPossible' => false,
   'carrierCodeFormattingRule' => NULL,
   'availableFormats' => 
  array (
    0 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$NP$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '([2-489])(\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '[2-489]',
      ),
       'format' => '$1-$2-$3',
       'intlFormat' => 
      array (
      ),
    )),
    1 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$NP$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '([57]\\d)(\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '[57]',
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
       'pattern' => '(1)([7-9]\\d{2})(\\d{3})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '1[7-9]',
      ),
       'format' => '$1-$2-$3-$4',
       'intlFormat' => 
      array (
      ),
    )),
    3 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(1255)(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '125',
      ),
       'format' => '$1-$2',
       'intlFormat' => 
      array (
      ),
    )),
    4 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(1200)(\\d{3})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '120',
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
       'pattern' => '(1212)(\\d{2})(\\d{2})',
       'leadingDigits' => 
      array (
        0 => '121',
      ),
       'format' => '$1-$2-$3',
       'intlFormat' => 
      array (
      ),
    )),
    6 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(1599)(\\d{6})',
       'leadingDigits' => 
      array (
        0 => '15',
      ),
       'format' => '$1-$2',
       'intlFormat' => 
      array (
      ),
    )),
    7 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '[2-689]',
      ),
       'format' => '*$1',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[17]\\d{6,9}|[2-589]\\d{3}(?:\\d{3,6})?|6\\d{3}',
     'possibleNumberPattern' => '\\d{4,10}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1700\\d{6}|[2-689]\\d{3}',
     'possibleNumberPattern' => '\\d{4,10}',
     'exampleNumber' => '1700123456',
  )),
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[2-489]\\d{7}',
     'possibleNumberPattern' => '\\d{7,8}',
     'exampleNumber' => '21234567',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '5(?:[02347-9]\\d{2}|5(?:2[23]|3[34]|4[45]|5[5689]|6[67]|7[78]|8[89])|6[2-9]\\d)\\d{5}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '501234567',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:80[019]\\d{3}|255)\\d{3}',
     'possibleNumberPattern' => '\\d{7,10}',
     'exampleNumber' => '1800123456',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:212|(?:9(?:0[01]|19)|200)\\d{2})\\d{4}',
     'possibleNumberPattern' => '\\d{8,10}',
     'exampleNumber' => '1919123456',
  )),
   'sharedCost' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1700\\d{6}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '1700123456',
  )),
   'personalNumber' => NULL,
   'voip' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '7(?:2[23]\\d|3[237]\\d|47\\d|6(?:5\\d|8[08])|7\\d{2}|8(?:33|55|77|81))\\d{5}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '771234567',
  )),
   'uan' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[2-689]\\d{3}',
     'possibleNumberPattern' => '\\d{4}',
     'exampleNumber' => '2250',
  )),
   'voicemail' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1599\\d{6}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '1599123456',
  )),
   'shortCode' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1\\d{3}',
     'possibleNumberPattern' => '\\d{4}',
     'exampleNumber' => '1455',
  )),
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:0[012]|12)',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '112',
  )),
));