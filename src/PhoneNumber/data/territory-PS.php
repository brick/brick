<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'PS',
   'countryCode' => '970',
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
       'pattern' => '([2489])(2\\d{2})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '[2489]',
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
       'pattern' => '(5[69]\\d)(\\d{3})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '5',
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
       'pattern' => '(1[78]00)(\\d{3})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '1[78]',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[24589]\\d{7,8}|1(?:[78]\\d{8}|[49]\\d{2,3})',
     'possibleNumberPattern' => '\\d{4,10}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:22[234789]|42[45]|82[01458]|92[369])\\d{5}',
     'possibleNumberPattern' => '\\d{7,8}',
     'exampleNumber' => '22234567',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '5[69]\\d{7}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '599123456',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1800\\d{6}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '1800123456',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:4|9\\d)\\d{2}',
     'possibleNumberPattern' => '\\d{4,5}',
     'exampleNumber' => '19123',
  )),
   'sharedCost' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1700\\d{6}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '1700123456',
  )),
   'personalNumber' => NULL,
   'voip' => NULL,
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => NULL,
));