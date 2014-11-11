<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'JO',
   'countryCode' => '962',
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
       'pattern' => '(\\d)(\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '[2356]|87',
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
       'pattern' => '(7)(\\d{4})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '7[457-9]',
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
       'pattern' => '(\\d{3})(\\d{5,6})',
       'leadingDigits' => 
      array (
        0 => '70|8[0158]|9',
      ),
       'format' => '$1 $2',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[235-9]\\d{7,8}',
     'possibleNumberPattern' => '\\d{7,9}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:2(?:6(?:2[0-35-9]|3[0-57-8]|4[24-7]|5[0-24-8]|[6-9][02])|7(?:0[1-79]|10|2[014-7]|3[0-689]|4[019]|5[0-3578]))|32(?:0[1-69]|1[1-35-7]|2[024-7]|3\\d|[457][02]|60)|53(?:[013][02]|2[0-59]|49|5[0-35-9]|6[15]|7[45]|8[1-6]|9[0-36-9])|6(?:2[50]0|300|4(?:0[0125]|1[2-7]|2[0569]|[38][07-9]|4[025689]|6[0-589]|7\\d|9[0-2])|5(?:[01][056]|2[034]|3[0-57-9]|4[17-8]|5[0-69]|6[0-35-9]|7[1-379]|8[0-68]|9[02-39]))|87(?:[02]0|7[08]|9[09]))\\d{4}',
     'possibleNumberPattern' => '\\d{7,8}',
     'exampleNumber' => '62001234',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '7(?:55|7[25-9]|8[5-9]|9[05-9])\\d{6}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '790123456',
  )),
   'pager' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '74(?:66|77)\\d{5}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '746612345',
  )),
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '80\\d{6}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '80012345',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '900\\d{5}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '90012345',
  )),
   'sharedCost' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '85\\d{6}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '85012345',
  )),
   'personalNumber' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '70\\d{7}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '700123456',
  )),
   'voip' => NULL,
   'uan' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '8(?:10|8\\d)\\d{5}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '88101234',
  )),
   'voicemail' => NULL,
   'shortCode' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:09|1[01]|9[024-79])',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '111',
  )),
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:12|91)|911',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '112',
  )),
));