<?php return Brick\PhoneNumber\Metadata\Territory::__set_state(array(
   'id' => 'ME',
   'countryCode' => '382',
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
       'pattern' => '(\\d{2})(\\d{3})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '[2-57-9]|6[3789]',
        1 => '[2-57-9]|6(?:[389]|7(?:[0-8]|9[3-9]))',
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
       'pattern' => '(67)(9)(\\d{3})(\\d{3})',
       'leadingDigits' => 
      array (
        0 => '679',
        1 => '679[0-2]',
      ),
       'format' => '$1 $2 $3 $4',
       'intlFormat' => 
      array (
      ),
    )),
  ),
   'generalDesc' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '[2-9]\\d{7,8}',
     'possibleNumberPattern' => '\\d{6,9}',
     'exampleNumber' => NULL,
  )),
   'noInternationalDialling' => NULL,
   'areaCodeOptional' => NULL,
   'fixedLine' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:20[2-8]|3(?:0[2-7]|1[35-7]|2[3567]|3[4-7])|4(?:0[237]|1[27])|5(?:0[47]|1[27]|2[378]))\\d{5}',
     'possibleNumberPattern' => '\\d{6,8}',
     'exampleNumber' => '30234567',
  )),
   'mobile' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '6(?:32\\d|[89]\\d{2}|7(?:[0-8]\\d|9(?:[3-9]|[0-2]\\d)))\\d{4}',
     'possibleNumberPattern' => '\\d{8,9}',
     'exampleNumber' => '67622901',
  )),
   'pager' => NULL,
   'tollFree' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '800[28]\\d{4}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '80080002',
  )),
   'premiumRate' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '(?:88\\d|9(?:4[13-8]|5[16-8]))\\d{5}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '94515151',
  )),
   'sharedCost' => NULL,
   'personalNumber' => NULL,
   'voip' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '78[1-9]\\d{5}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '78108780',
  )),
   'uan' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '77\\d{6}',
     'possibleNumberPattern' => '\\d{8}',
     'exampleNumber' => '77273012',
  )),
   'voicemail' => NULL,
   'shortCode' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:16\\d{3}|2(?:[015-9]|\\d{2})|[0135]\\d{2}|4\\d{2,3}|9\\d{3})',
     'possibleNumberPattern' => '\\d{3,6}',
     'exampleNumber' => '1011',
  )),
   'emergency' => 
  Brick\PhoneNumber\Metadata\NumberPattern::__set_state(array(
     'nationalNumberPattern' => '1(?:12|2[234])',
     'possibleNumberPattern' => '\\d{3}',
     'exampleNumber' => '112',
  )),
));