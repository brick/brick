<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'EE',
   'countryCode' => '372',
   'mainCountryForCode' => false,
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => NULL,
   'internationalPrefix' => '00',
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
       'pattern' => '([3-79]\\d{2})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '[369]|4[3-8]|5(?:[0-2]|5[0-478]|6[45])|7[1-9]',
        1 => '[369]|4[3-8]|5(?:[02]|1(?:[0-8]|95)|5[0-478]|6(?:4[0-4]|5[1-589]))|7[1-9]',
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
       'pattern' => '(70)(\\d{2})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '70',
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
       'pattern' => '(8000)(\\d{3})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '800',
        1 => '8000',
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
       'pattern' => '([458]\\d{3})(\\d{3,4})',
       'leadingDigits' => 
      array (
        0 => '40|5|8(?:00|[1-5])',
        1 => '40|5|8(?:00[1-9]|[1-5])',
      ),
       'format' => '$1 $2',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1\\d{3,4}|[3-9]\\d{6,7}|800\\d{6,7}',
     'possibleNumberPattern' => '\\d{4,10}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1\\d{3,4}|800[2-9]\\d{3}',
     'possibleNumberPattern' => '\\d{4,7}',
     'exampleNumber' => '8002123',
  )),
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:3[23589]|4(?:0\\d|[3-8])|6\\d|7[1-9]|88)\\d{5}',
     'possibleNumberPattern' => '\\d{7,8}',
     'exampleNumber' => '3212345',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:5\\d|8[1-5])\\d{6}|5(?:[02]\\d{2}|1(?:[0-8]\\d|95)|5[0-478]\\d|64[0-4]|65[1-589])\\d{3}',
     'possibleNumberPattern' => '\\d{7,8}',
     'exampleNumber' => '51234567',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '800(?:0\\d{3}|1\\d|[2-9])\\d{3}',
     'possibleNumberPattern' => '\\d{7,10}',
     'exampleNumber' => '80012345',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '900\\d{4}',
     'possibleNumberPattern' => '\\d{7}',
     'exampleNumber' => '9001234',
  )),
   'sharedCost' => NULL,
   'personalNumber' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '70[0-2]\\d{5}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '70012345',
  )),
   'voip' => NULL,
   'uan' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:2[01245]|3[0-6]|4[1-489]|5[0-59]|6[1-46-9]|7[0-27-9]|8[189]|9[012])\\d{1,2}',
     'possibleNumberPattern' => '\\d{4,5}',
     'exampleNumber' => '12123',
  )),
   'voicemail' => NULL,
   'shortCode' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:1[13-9]|[2-9]\\d)',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '116',
  )),
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '11[02]',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '112',
  )),
));