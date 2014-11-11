<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'GT',
   'countryCode' => '502',
   'mainCountryForCode' => false,
   'leadingDigits' => NULL,
   'preferredInternationalPrefix' => NULL,
   'internationalPrefix' => '00',
   'nationalPrefix' => NULL,
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
       'nationalPrefixFormattingRule' => NULL,
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{4})(\\d{4})',
       'leadingDigits' => 
      array (
        0 => '[2-7]',
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
       'pattern' => '(\\d{4})(\\d{3})(\\d{4})',
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
     'nationalNumberPattern' => '[2-7]\\d{7}|1[89]\\d{9}',
     'possibleNumberPattern' => '\\d{8}(?:\\d{3})?',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[267][2-9]\\d{6}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '22456789',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[345]\\d{7}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '51234567',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '18[01]\\d{8}',
     'possibleNumberPattern' => '\\d{11}',
     'exampleNumber' => '18001112222',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '19\\d{9}',
     'possibleNumberPattern' => '\\d{11}',
     'exampleNumber' => '19001112222',
  )),
   'sharedCost' => NULL,
   'personalNumber' => NULL,
   'voip' => NULL,
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:2[124-9]|[57]\\d{2})',
     'possibleNumberPattern' => '\\d{3,4}',
     'exampleNumber' => '123',
  )),
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:10|2[03])',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '110',
  )),
));