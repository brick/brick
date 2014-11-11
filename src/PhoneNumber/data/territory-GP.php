<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'GP',
   'countryCode' => '590',
   'mainCountryForCode' => 'true',
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
       'pattern' => '([56]90)(\\d{2})(\\d{4})',
       'leadingDigits' => 
      array (
      ),
       'format' => '$1 $2-$3',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[56]\\d{8}',
     'possibleNumberPattern' => '\\d{9}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '590(?:1[12]|2[0-68]|3[28]|4[126-8]|5[067]|6[018]|[89]\\d)\\d{4}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '590201234',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '690(?:00|1[1-9]|2[013-5]|[3-5]\\d|6[0-57-9]|7[1-6]|8[0-6]|9[09])\\d{4}',
     'possibleNumberPattern' => NULL,
     'exampleNumber' => '690301234',
  )),
   'pager' => NULL,
   'tollFree' => NULL,
   'premiumRate' => NULL,
   'sharedCost' => NULL,
   'personalNumber' => NULL,
   'voip' => NULL,
   'uan' => NULL,
   'voicemail' => NULL,
   'shortCode' => NULL,
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1[578]',
     'possibleNumberPattern' => '\\d{2}',
     'exampleNumber' => '18',
  )),
));