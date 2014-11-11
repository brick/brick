<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'CN',
   'countryCode' => '86',
   'mainCountryForCode' => false,
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => '00',
   'internationalPrefix' => '(1[1279]\\d{3})?00',
   'nationalPrefix' => '0',
   'nationalPrefixForParsing' => '(1[1279]\\d{3})|0',
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
       'nationalPrefixFormattingRule' => '$NP$FG',
       'nationalPrefixOptionalWhenFormatting' => 'true',
       'carrierCodeFormattingRule' => '$CC $FG',
       'pattern' => '(80\\d{2})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '80[2678]',
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
       'pattern' => '([48]00)(\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '[48]00',
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
       'pattern' => '(\\d{5})',
       'leadingDigits' => 
      array (
        0 => '95',
      ),
       'format' => '$1',
       'intlFormat' => 
      array (
      ),
    )),
    3 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{3,4})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '[2-9]',
      ),
       'format' => '$1 $2',
       'intlFormat' => 
      array (
        0 => 'NA',
      ),
    )),
    4 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$NP$FG',
       'nationalPrefixOptionalWhenFormatting' => 'true',
       'carrierCodeFormattingRule' => '$CC $FG',
       'pattern' => '(21)(\\d{4})(\\d{4,6})',
       'leadingDigits' => 
      array (
        0 => '21',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
    5 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$NP$FG',
       'nationalPrefixOptionalWhenFormatting' => 'true',
       'carrierCodeFormattingRule' => '$CC $FG',
       'pattern' => '([12]\\d)(\\d{4})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '10[1-9]|2[02-9]',
        1 => '10[1-9]|2[02-9]',
        2 => '10(?:[1-79]|8(?:[1-9]|0[1-9]))|2[02-9]',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
    6 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$NP$FG',
       'nationalPrefixOptionalWhenFormatting' => 'true',
       'carrierCodeFormattingRule' => '$CC $FG',
       'pattern' => '(\\d{3})(\\d{4})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '3(?:11|7[179])|4(?:[15]1|3[12])|5(?:1|2[37]|3[12]|51|7[13-79]|9[15])|7(?:31|5[457]|6[09]|91)|8(?:71|98)',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
    7 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$NP$FG',
       'nationalPrefixOptionalWhenFormatting' => 'true',
       'carrierCodeFormattingRule' => '$CC $FG',
       'pattern' => '(\\d{3})(\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '3(?:1[02-9]|35|49|5|7[02-68]|9[1-68])|4(?:1[02-9]|2[179]|[35][2-9]|6[4789]|7\\d|8[23])|5(?:3[03-9]|4[36]|5[02-9]|6[1-46]|7[028]|80|9[2-46-9])|6(?:3[1-5]|6[0238]|9[12])|7(?:01|[1579]|2[248]|3[04-9]|4[3-6]|6[2368])|8(?:1[236-8]|2[5-7]|3|5[1-9]|7[02-9]|8[3678]|9[1-7])|9(?:0[1-3689]|1[1-79]|[379]|4[13]|5[1-5])',
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
       'carrierCodeFormattingRule' => '$CC $FG',
       'pattern' => '(1[3-58]\\d)(\\d{4})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '1[3-58]',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
    9 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(10800)(\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '108',
        1 => '1080',
        2 => '10800',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[1-7]\\d{7,11}|8[0-357-9]\\d{6,9}|9(?:5\\d{3}|\\d{9})',
     'possibleNumberPattern' => '\\d{4,12}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:4|(?:10)?8)00\\d{7}|95\\d{3}',
     'possibleNumberPattern' => '\\d{5,12}',
     'exampleNumber' => '4001234567',
  )),
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '21\\d{8,10}|(?:10|2[02-57-9]|3(?:11|7[179])|4(?:[15]1|3[12])|5(?:1\\d|2[37]|3[12]|51|7[13-79]|9[15])|7(?:31|5[457]|6[09]|91)|8(?:71|98))\\d{8}|(?:3(?:1[02-9]|35|49|5\\d|7[02-68]|9[1-68])|4(?:1[02-9]|2[179]|3[3-9]|5[2-9]|6[4789]|7\\d|8[23])|5(?:3[03-9]|4[36]|5[02-9]|6[1-46]|7[028]|80|9[2-46-9])|6(?:3[1-5]|6[0238]|9[12])|7(?:01|[17]\\d|2[248]|3[04-9]|4[3-6]|5[0-3689]|6[2368]|9[02-9])|8(?:1[236-8]|2[5-7]|3\\d|5[1-9]|7[02-9]|8[3678]|9[1-7])|9(?:0[1-3689]|1[1-79]|[379]\\d|4[13]|5[1-5]))\\d{7}|80(?:29|6[03578]|7[018]|81)\\d{4}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '1012345678',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:3\\d|4[57]|[58][0-35-9])\\d{8}',
     'possibleNumberPattern' => '\\d{11}',
     'exampleNumber' => '13123456789',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:10)?800\\d{7}',
     'possibleNumberPattern' => '\\d{10,12}',
     'exampleNumber' => '8001234567',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '16[08]\\d{5}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '16812345',
  )),
   'sharedCost' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '400\\d{7}|95\\d{3}',
     'possibleNumberPattern' => '\\d{5}(?:\\d{5})?',
     'exampleNumber' => '4001234567',
  )),
   'personalNumber' => NULL,
   'voip' => NULL,
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:1[09]|20)',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '119',
  )),
));