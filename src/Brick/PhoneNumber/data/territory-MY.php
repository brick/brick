<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'MY',
   'countryCode' => '60',
   'mainCountryForCode' => false,
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => NULL,
   'internationalPrefix' => '00',
   'nationalPrefix' => '0',
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
       'nationalPrefixFormattingRule' => '$NP$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '([4-79])(\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '[4-79]',
      ),
       'format' => '$1-$2 $3',
       'intlFormat' => 
      array (
      ),
    )),
    1 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$NP$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(3)(\\d{4})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '3',
      ),
       'format' => '$1-$2 $3',
       'intlFormat' => 
      array (
      ),
    )),
    2 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$NP$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '([18]\\d)(\\d{3})(\\d{3,4})',
       'leadingDigits' => 
      array (
        0 => '1[02-46-9][1-9]|8',
      ),
       'format' => '$1-$2 $3',
       'intlFormat' => 
      array (
      ),
    )),
    3 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(1)([36-8]00)(\\d{2})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '1[36-8]0',
      ),
       'format' => '$1-$2-$3-$4',
       'intlFormat' => 
      array (
      ),
    )),
    4 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$NP$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(11)(\\d{4})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '11',
      ),
       'format' => '$1-$2 $3',
       'intlFormat' => 
      array (
      ),
    )),
    5 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$NP$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(154)(\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '15',
      ),
       'format' => '$1-$2 $3',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[13-9]\\d{7,9}',
     'possibleNumberPattern' => '\\d{6,10}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:3[2-9]\\d|[4-9][2-9])\\d{6}',
     'possibleNumberPattern' => '\\d{6,9}',
     'exampleNumber' => '323456789',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:1[1-3]\\d{2}|[02-4679][2-9]\\d|8(?:1[23]|[2-9]\\d))\\d{5}',
     'possibleNumberPattern' => '\\d{9,10}',
     'exampleNumber' => '123456789',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1[38]00\\d{6}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '1300123456',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1600\\d{6}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '1600123456',
  )),
   'sharedCost' => NULL,
   'personalNumber' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1700\\d{6}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '1700123456',
  )),
   'voip' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '154\\d{7}',
     'possibleNumberPattern' => '\\d{10}',
     'exampleNumber' => '1541234567',
  )),
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '112|999',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '999',
  )),
));