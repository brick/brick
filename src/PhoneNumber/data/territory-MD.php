<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'MD',
   'countryCode' => '373',
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
        0 => '22|3',
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
       'pattern' => '([25-7]\\d{2})(\\d{2})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '2[13-79]|[5-7]',
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
       'pattern' => '([89]\\d{2})(\\d{5})',
       'leadingDigits' => 
      array (
        0 => '[89]',
      ),
       'format' => '$1 $2',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[235-9]\\d{7}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:2(?:1[0569]|2\\d|3[015-7]|4[1-46-9]|5[0-24689]|6[2-589]|7[1-37]|9[1347-9])|5(?:33|5[257]))\\d{5}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '22212345',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:562|6(?:50|7[1-5]|[089]\\d)|7(?:7[47-9]|[89]\\d))\\d{5}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '65012345',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '800\\d{5}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '80012345',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '90[056]\\d{5}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '90012345',
  )),
   'sharedCost' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '808\\d{5}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '80812345',
  )),
   'personalNumber' => NULL,
   'voip' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '3[08]\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '30123456',
  )),
   'uan' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '8(?:03|14)\\d{5}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '80312345',
  )),
   'voicemail' => NULL,
   'shortCode' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:1(?:[79]|6(?:000|1(?:11|23))|8\\d)|4\\d{3}|5[0-3]\\d|6[0-389]\\d|8\\d{2}|9(?:0[04-9]|[1-4]\\d))',
     'possibleNumberPattern' => '\\d{3,6}',
     'exampleNumber' => '116000',
  )),
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '112|90[123]',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '112',
  )),
));