<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'UY',
   'countryCode' => '598',
   'mainCountryForCode' => false,
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => '00',
   'internationalPrefix' => '0(?:1[3-9]\\d|0)',
   'nationalPrefix' => '0',
   'nationalPrefixForParsing' => NULL,
   'nationalPrefixTransformRule' => NULL,
   'preferredExtnPrefix' => ' int. ',
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
       'pattern' => '(\\d{4})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '[24]',
      ),
       'format' => '$1 $2',
       'intlFormat' => 
      array (
      ),
    )),
    1 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$NP$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{2})(\\d{3})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '9[1-9]',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
    2 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$NP$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '[89]0',
      ),
       'format' => '$1 $2',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[2489]\\d{6,7}',
     'possibleNumberPattern' => '\\d{7,8}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '2\\d{7}|4[2-7]\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '21231234',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '9[13-9]\\d{6}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '94231234',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '80[05]\\d{4}',
     'possibleNumberPattern' => '\\d{7}',
     'exampleNumber' => '8001234',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '90[0-8]\\d{4}',
     'possibleNumberPattern' => '\\d{7}',
     'exampleNumber' => '9001234',
  )),
   'sharedCost' => NULL,
   'personalNumber' => NULL,
   'voip' => NULL,
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:0[4-9]|1[2368]|2[0-3568])',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '104',
  )),
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '128|911',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '911',
  )),
));