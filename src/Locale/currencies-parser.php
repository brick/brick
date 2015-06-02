<?php

$data = file_get_contents('http://www.currency-iso.org/dam/downloads/table_a1.xml');

$document = new DOMDocument();
$document->loadXML($data);

$countries = $document->getElementsByTagName('CcyNtry');
$countryToCurrency = [];

foreach ($countries as $country) {
    /** @var DOMElement $country */
    $countryName = getDomElementString($country, 'CtryNm');
    $currencyCode = getDomElementString($country, 'Ccy');

    if ($currencyCode !== null && preg_match('/^[A-Z]{3}$/', $currencyCode) == 0) {
        throw new \RuntimeException('Invalid currency code: ' . $currencyCode);
    }

    $countryToCurrency[$countryName] = $currencyCode;
}

file_put_contents('country-to-currency.php', sprintf("<?php return %s;\n", var_export($countryToCurrency, true)));

/**
 * @param DOMElement $element
 * @param string     $name
 *
 * @return string|null
 */
function getDomElementString(DOMElement $element, $name)
{
    foreach ($element->getElementsByTagName($name) as $child) {
        /** @var $child DOMElement */
        return $child->textContent;
    }

    return null;
}
