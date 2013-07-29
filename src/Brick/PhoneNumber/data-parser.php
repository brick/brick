<?php

require __DIR__ . '/Metadata/AbstractMetadata.php';
require __DIR__ . '/Metadata/Territory.php';
require __DIR__ . '/Metadata/NumberFormat.php';
require __DIR__ . '/Metadata/NumberPattern.php';

use Brick\PhoneNumber\Metadata\Territory;
use Brick\PhoneNumber\Metadata\NumberFormat;
use Brick\PhoneNumber\Metadata\NumberPattern;

foreach (glob('data/*.php') as $file) {
    unlink($file);
}

$file = 'google-data/PhoneNumberMetaData.xml';
$xml = simplexml_load_file($file);

$numberPatterns = array(
    'generalDesc', 'noInternationalDialling', 'areaCodeOptional', 'fixedLine',
    'mobile', 'pager', 'tollFree', 'premiumRate', 'sharedCost', 'personalNumber',
    'voip', 'uan', 'voicemail', 'shortCode', 'emergency'
);

$countryCodeToRegionCodes = array();
$regionCodeToCountryCode = array();

foreach ($xml->territories->territory as $xmlTerritory) {
    $territory = new Territory();
    mapAttributes($xmlTerritory, $territory);

    foreach ($numberPatterns as $patternName) {
        $xmlPattern = $xmlTerritory->$patternName;

        if ($xmlPattern->count() == 1) {
            $xmlPattern = $xmlPattern[0];
            $numberPattern = new NumberPattern();

            foreach ($xmlPattern->children() as $child) {
                mapChildren($xmlPattern, $numberPattern);
            }

            setProperty($territory, $patternName, $numberPattern);
        }
    }

    $availableFormats = $xmlTerritory->availableFormats;
    if ($availableFormats->count() == 1) {
        $availableFormats = $availableFormats[0];
        $numberFormats = array();
        foreach ($availableFormats->numberFormat as $xmlNumberFormat) {
            $numberFormat = new NumberFormat();
            mapAttributes($xmlNumberFormat, $numberFormat);

            mapChildren($xmlNumberFormat, $numberFormat, array('leadingDigits', 'intlFormat'));
            $numberFormats[] = $numberFormat;
        }

        setProperty($territory, 'availableFormats', $numberFormats);
    }

    if ($territory->id == '001') {
        $countryCodeToRegionCodes[$territory->countryCode] = array();
    } else {
        $countryCodeToRegionCodes[$territory->countryCode][] = $territory->id;
        $regionCodeToCountryCode[$territory->id] = $territory->countryCode;
    }

    $code = ($territory->id == '001') ? $territory->countryCode : $territory->id;
    writePhpData('territory-' . $code, $territory);
}

writePhpData('country-code-to-region-codes', $countryCodeToRegionCodes);
writePhpData('region-code-to-country-code', $regionCodeToCountryCode);

/**
 * @param string $key
 * @param string $data
 * @return void
 */
function writePhpData($key, $data)
{
    file_put_contents('data/' . $key . '.php', '<?php return ' . var_export($data, true) . ';');
}

/**
 * @param \SimpleXMLElement $element
 * @param object $object
 * @return void
 * @throws \RuntimeException
 */
function mapAttributes(\SimpleXMLElement $element, $object)
{
    foreach ($element->attributes() as $key => $value) {
        setProperty($object, $key, (string) $value);
    }
}

/**
 * @param \SimpleXMLElement $element
 * @param object $object
 * @param array $multiple The element names that can appear multiple times, and result in an array of strings.
 * @return void
 * @throws \RuntimeException
 */
function mapChildren(\SimpleXMLElement $element, $object, array $multiple = array())
{
    $values = array();

    foreach ($element->children() as $child) {
        $name = $child->getName();
        if (in_array($name, $multiple)) {
            $values[$name][] = (string) $child;
        } else {
            $values[$name] = (string) $child;
        }
    }

    foreach ($values as $name => $value) {
        setProperty($object, $name, $value);
    }
}

/**
 * @param object $object
 * @param string $name
 * @param string|array $value
 *
 * @return void
 *
 * @throws \RuntimeException
 */
function setProperty($object, $name, $value)
{
    if ($name == 'leadingDigits' || $name == 'pattern' || $name == 'nationalPrefixForParsing' || substr($name, -7, 7) == 'Pattern') {
        $value = preg_replace('/\s+/', '', $value);
    }

    $r = new \ReflectionObject($object);

    if ($r->hasProperty($name)) {
        $object->$name = $value;
    } else {
        echo 'Warning: ' . get_class($object) . ' has no property $' . $name . PHP_EOL;
    }
}
