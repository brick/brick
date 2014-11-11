<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'BE',
   'countryCode' => '32',
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
       'pattern' => '(4[6-9]\\d)(\\d{2})(\\d{2})(\\d{2})',
       'leadingDigits' => 
      array (
        0 => '4[6-9]',
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
       'pattern' => '([2-49])(\\d{3})(\\d{2})(\\d{2})',
       'leadingDigits' => 
      array (
        0 => '[23]|[49][23]',
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
       'pattern' => '([15-8]\\d)(\\d{2})(\\d{2})(\\d{2})',
       'leadingDigits' => 
      array (
        0 => '[156]|7[0178]|8(?:0[1-9]|[1-79])',
      ),
       'format' => '$1 $2 $3 $4',
       'intlFormat' => 
      array (
      ),
    )),
    3 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '([89]\\d{2})(\\d{2})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '(?:80|9)0',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[1-9]\\d{7,8}',
     'possibleNumberPattern' => '\\d{8,9}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:1[0-69]|[23][2-8]|[49][23]|5\\d|6[013-57-9]|71)\\d{6}|8(?:0[1-9]|[1-79]\\d)\\d{5}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '12345678',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '4(?:[679]\\d|8[03-9])\\d{6}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '470123456',
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
     'nationalNumberPattern' => '(?:90|7[07])\\d{6}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '90123456',
  )),
   'sharedCost' => NULL,
   'personalNumber' => NULL,
   'voip' => NULL,
   'uan' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '78\\d{6}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '78123456',
  )),
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:0[01]|12)',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '112',
  )),
));