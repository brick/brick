<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'AE',
   'countryCode' => '971',
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
       'pattern' => '([2-4679])(\\d{3})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '[2-4679][2-8]',
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
       'pattern' => '(5[0256])(\\d{3})(\\d{4})',
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
       'pattern' => '([479]00)(\\d)(\\d{5})',
       'leadingDigits' => 
      array (
        0 => '[479]0',
      ),
       'format' => '$1 $2 $3',
       'intlFormat' => 
      array (
      ),
    )),
    3 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '([68]00)(\\d{2,9})',
       'leadingDigits' => 
      array (
        0 => '60|8',
      ),
       'format' => '$1 $2',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[2-79]\\d{7,8}|800\\d{2,9}',
     'possibleNumberPattern' => '\\d{5,12}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[2-4679][2-8]\\d{6}',
     'possibleNumberPattern' => '\\d{7,8}',
     'exampleNumber' => '22345678',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '5[0256]\\d{7}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '501234567',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '400\\d{6}|800\\d{2,9}',
     'possibleNumberPattern' => '\\d{5,12}',
     'exampleNumber' => '800123456',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '900[02]\\d{5}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '900234567',
  )),
   'sharedCost' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '700[05]\\d{5}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '700012345',
  )),
   'personalNumber' => NULL,
   'voip' => NULL,
   'uan' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '600[25]\\d{5}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '600212345',
  )),
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '112|99[789]',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '112',
  )),
));