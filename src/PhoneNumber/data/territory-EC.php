<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'EC',
   'countryCode' => '593',
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
       'pattern' => '(\\d)(\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '[247]|[356][2-8]',
      ),
       'format' => '$1 $2-$3',
       'intlFormat' => 
      array (
        0 => '$1-$2-$3',
      ),
    )),
    1 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$NP$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{2})(\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '9',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
    2 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(1800)(\\d{3})(\\d{3,4})',
       'leadingDigits' => 
      array (
        0 => '1',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1\\d{9,10}|[2-8]\\d{7}|9\\d{8}',
     'possibleNumberPattern' => '\\d{7,11}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[2-7][2-7]\\d{6}',
     'possibleNumberPattern' => '\\d{7,8}',
     'exampleNumber' => '22123456',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '9(?:39|[4-6][89]|7[7-9]|[89]\\d)\\d{6}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '991234567',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1800\\d{6,7}',
     'possibleNumberPattern' => '\\d{10,11}',
     'exampleNumber' => '18001234567',
  )),
   'premiumRate' => NULL,
   'sharedCost' => NULL,
   'personalNumber' => NULL,
   'voip' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[2-7]890\\d{4}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '28901234',
  )),
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:0[12]|12)|911',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '911',
  )),
));