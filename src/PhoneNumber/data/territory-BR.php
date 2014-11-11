<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'BR',
   'countryCode' => '55',
   'mainCountryForCode' => false,
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => NULL,
   'internationalPrefix' => '00(?:1[45]|2[135]|[34]1|43)',
   'nationalPrefix' => '0',
   'nationalPrefixForParsing' => '0(?:(1[245]|2[135]|[34]1)(\\d{10,11}))?',
   'nationalPrefixTransformRule' => '$2',
   'preferredExtnPrefix' => NULL,
   'nationalPrefixFormattingRule' => NULL,
   'nationalPrefixOptionalWhenFormatting' => false,
   'leadingZeroPossible' => false,
   'carrierCodeFormattingRule' => NULL,
   'availableFormats' => 
  array (
    0 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{4})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '[2-9](?:[1-9]|0[1-9])',
      ),
       'format' => '$1-$2',
       'intlFormat' => 
      array (
        0 => 'NA',
      ),
    )),
    1 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{5})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '9(?:[1-9]|0[1-9])',
      ),
       'format' => '$1-$2',
       'intlFormat' => 
      array (
        0 => 'NA',
      ),
    )),
    2 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '($FG)',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => '$NP $CC ($FG)',
       'pattern' => '(\\d{2})(\\d{5})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '119',
      ),
       'format' => '$1 $2-$3',
       'intlFormat' => 
      array (
      ),
    )),
    3 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '($FG)',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => '$NP $CC ($FG)',
       'pattern' => '(\\d{2})(\\d{4})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '[1-9][1-9]',
      ),
       'format' => '$1 $2-$3',
       'intlFormat' => 
      array (
      ),
    )),
    4 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '([34]00\\d)(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '[34]00',
      ),
       'format' => '$1-$2',
       'intlFormat' => 
      array (
      ),
    )),
    5 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$NP$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '([3589]00)(\\d{2,3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '[3589]00',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[1-46-9]\\d{7,10}|5\\d{8,9}',
     'possibleNumberPattern' => '\\d{8,11}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[34]00\\d{5}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '40041234',
  )),
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1[1-9][2-5]\\d{7}|(?:[4689][1-9]|2[12478]|3[1-578]|5[13-5]|7[13-579])[2-5]\\d{7}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '1123456789',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:1(?:5[347]|[6-8]\\d|9\\d{1,2})|[2-9][6-9]\\d)\\d{6}|(?:[4689][1-9]|2[12478]|3[1-578]|5[13-5]|7[13-579])[6-9]\\d{7}',
     'possibleNumberPattern' => '\\d{10,11}',
     'exampleNumber' => '1161234567',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '800\\d{6,7}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '800123456',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[359]00\\d{6,7}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '300123456',
  )),
   'sharedCost' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[34]00\\d{5}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '40041234',
  )),
   'personalNumber' => NULL,
   'voip' => NULL,
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:12|28|9[023])|911',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '190',
  )),
));