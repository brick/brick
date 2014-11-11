<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'IT',
   'countryCode' => '39',
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
   'leadingZeroPossible' => 'true',
   'carrierCodeFormattingRule' => NULL,
   'availableFormats' => 
  array (
    0 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{2})(\\d{3,4})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '0[26]|55',
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
       'pattern' => '(0[26])(\\d{4})(\\d{5})',
       'leadingDigits' => 
      array (
        0 => '0[26]',
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
       'pattern' => '(0[26])(\\d{4,6})',
       'leadingDigits' => 
      array (
        0 => '0[26]',
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
       'pattern' => '(0\\d{2})(\\d{3,4})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '0[13-57-9][0159]',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
    4 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{3})(\\d{3,6})',
       'leadingDigits' => 
      array (
        0 => '0[13-57-9][0159]|8(?:03|4[17]|9[245])',
        1 => '0[13-57-9][0159]|8(?:03|4[17]|9(?:2|[45][0-4]))',
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
       'pattern' => '(0\\d{3})(\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '0[13-57-9][2-46-8]',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
    6 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(0\\d{3})(\\d{2,6})',
       'leadingDigits' => 
      array (
        0 => '0[13-57-9][2-46-8]',
      ),
       'format' => '$1 $2',
       'intlFormat' => 
      array (
      ),
    )),
    7 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{3})(\\d{3})(\\d{3,4})',
       'leadingDigits' => 
      array (
        0 => '[13]|8(?:00|4[08]|9[59])',
        1 => '[13]|8(?:00|4[08]|9(?:5[5-9]|9))',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
    8 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{4})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '894',
        1 => '894[5-9]',
      ),
       'format' => '$1 $2',
       'intlFormat' => 
      array (
      ),
    )),
    9 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{3})(\\d{4})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '3',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[01589]\\d{5,10}|3(?:[12457-9]\\d{8}|[36]\\d{7,9})',
     'possibleNumberPattern' => '\\d{6,11}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '848\\d{6}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '848123456',
  )),
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '0(?:[26]\\d{4,9}|(?:1(?:[0159]\\d|[27][1-5]|31|4[1-4]|6[1356]|8[2-57])|3(?:[0159]\\d|2[1-4]|3[12]|[48][1-6]|6[2-59]|7[1-7])|4(?:[0159]\\d|[23][1-9]|4[245]|6[1-5]|7[1-4]|81)|5(?:[0159]\\d|2[1-5]|3[2-6]|4[1-79]|6[4-6]|7[1-578]|8[3-8])|7(?:[0159]\\d|2[12]|3[1-7]|4[2346]|6[13569]|7[13-6]|8[1-59])|8(?:[0159]\\d|2[34578]|3[1-356]|[6-8][1-5])|9(?:[0159]\\d|[238][1-5]|4[12]|6[1-8]|7[1-6]))\\d{2,7})',
     'possibleNumberPattern' => '\\d{6,11}',
     'exampleNumber' => '0212345678',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '3(?:[12457-9]\\d{8}|6\\d{7,8}|3\\d{7,9})',
     'possibleNumberPattern' => '\\d{9,11}',
     'exampleNumber' => '3123456789',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '80(?:0\\d{6}|3\\d{3})',
     'possibleNumberPattern' => '\\d{6,9}',
     'exampleNumber' => '800123456',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '0878\\d{5}|1(?:44|6[346])\\d{6}|89(?:2\\d{3}|4(?:[0-4]\\d{2}|[5-9]\\d{4})|5(?:[0-4]\\d{2}|[5-9]\\d{6})|9\\d{6})',
     'possibleNumberPattern' => '\\d{6,10}',
     'exampleNumber' => '899123456',
  )),
   'sharedCost' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '84(?:[08]\\d{6}|[17]\\d{3})',
     'possibleNumberPattern' => '\\d{6,9}',
     'exampleNumber' => '848123456',
  )),
   'personalNumber' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:78\\d|99)\\d{6}',
     'possibleNumberPattern' => '\\d{9,10}',
     'exampleNumber' => '1781234567',
  )),
   'voip' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '55\\d{8}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '5512345678',
  )),
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:1(?:[47]|6\\d{3})|2\\d{2}|4(?:82|9\\d{1,3})|5(?:00|1[58]|2[25]|3[03]|44)|86|9(?:2(?:[01]\\d{2}|[2-9]\\d)|4\\d|696))',
     'possibleNumberPattern' => '\\d{3,6}',
     'exampleNumber' => '114',
  )),
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '11[2358]',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '112',
  )),
));