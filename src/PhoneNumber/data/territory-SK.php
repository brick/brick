<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'SK',
   'countryCode' => '421',
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
       'pattern' => '(2)(\\d{3})(\\d{3})(\\d{2})',
       'leadingDigits' => 
      array (
        0 => '2',
      ),
       'format' => '$1/$2 $3 $4',
       'intlFormat' => 
      array (
      ),
    )),
    1 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '([3-5]\\d)(\\d{3})(\\d{2})(\\d{2})',
       'leadingDigits' => 
      array (
        0 => '[3-5]',
      ),
       'format' => '$1/$2 $3 $4',
       'intlFormat' => 
      array (
      ),
    )),
    2 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '([689]\\d{2})(\\d{3})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '[689]',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[2-689]\\d{8}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:8(?:00|[5-9]\\d)|9(?:00|[78]\\d))\\d{6}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '800123456',
  )),
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[2-5]\\d{8}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '212345678',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '9(?:0[1-8]|1[0-24-9]|4[0489])\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '912123456',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '800\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '800123456',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '9(?:[78]\\d{7}|00\\d{6})',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '900123456',
  )),
   'sharedCost' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '8[5-9]\\d{7}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '850123456',
  )),
   'personalNumber' => NULL,
   'voip' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '6(?:5[0-4]|9[0-6])\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '690123456',
  )),
   'uan' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '96\\d{7}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '961234567',
  )),
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:12|5[058])',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '112',
  )),
));