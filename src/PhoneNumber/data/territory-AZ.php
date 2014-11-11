<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'AZ',
   'countryCode' => '994',
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
       'pattern' => '(\\d{2})(\\d{3})(\\d{2})(\\d{2})',
       'leadingDigits' => 
      array (
        0 => '(?:1[28]|2(?:[45]2|[0-36])|365)',
      ),
       'format' => '$1 $2 $3 $4',
       'intlFormat' => 
      array (
      ),
    )),
    1 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$NP$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{2})(\\d{3})(\\d{2})(\\d{2})',
       'leadingDigits' => 
      array (
        0 => '[4-8]',
      ),
       'format' => '$1 $2 $3 $4',
       'intlFormat' => 
      array (
      ),
    )),
    2 => 
    Brick\PhoneNumber\Metadata\NumberFormat::__set_state(array(
       'nationalPrefixFormattingRule' => '$NP$FG',
       'nationalPrefixOptionalWhenFormatting' => false,
       'carrierCodeFormattingRule' => NULL,
       'pattern' => '(\\d{3})(\\d{2})(\\d{2})(\\d{2})',
       'leadingDigits' => 
      array (
        0 => '9',
      ),
       'format' => '$1 $2 $3 $4',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[1-9]\\d{8}',
     'possibleNumberPattern' => '\\d{7,9}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:1[28]\\d|2(?:02|1[24]|2[2-4]|33|[45]2|6[23])|365)\\d{6}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '123123456',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:4[04]|5[015]|60|7[07])\\d{7}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '401234567',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '88\\d{7}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '881234567',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '900200\\d{3}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => '900200123',
  )),
   'sharedCost' => NULL,
   'personalNumber' => NULL,
   'voip' => NULL,
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:0[123]|12)',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '101',
  )),
));