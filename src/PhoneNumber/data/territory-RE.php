<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'RE',
   'countryCode' => '262',
   'mainCountryForCode' => 'true',
   'leadingDigits' => '262|6[49]|8',
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
       'pattern' => '([268]\\d{2})(\\d{2})(\\d{2})(\\d{2})',
       'leadingDigits' => 
      array (
      ),
       'format' => '$1 $2 $3 $4',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[268]\\d{8}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '262\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '262161234',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '6(?:9[23]|47)\\d{6}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '692123456',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '80\\d{7}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '801234567',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '89[1-37-9]\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '891123456',
  )),
   'sharedCost' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '8(?:1[019]|2[0156]|84|90)\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '810123456',
  )),
   'personalNumber' => NULL,
   'voip' => NULL,
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:12|[578])',
     'possibleNumberPattern' => '\\d{2,3}',
     'exampleNumber' => '15',
  )),
));