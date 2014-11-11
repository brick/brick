<?php

/**
 * The currencies-parser.php script must be run first to create an intermediate file,
 * country-to-currency.php, which is necessary for this script to run.
 *
 * The intermediate file does not need to be under version control.
 */

$countryToCurrency = require 'country-to-currency.php';

$url = 'http://www.iso.org/iso/home/standards/country_codes/country_names_and_code_elements_txt.htm';
$data = file_get_contents($url);
$lines = preg_split('/[\r\n]+/', $data);

$countries = [];
$countryNameToCode = [];

foreach ($lines as $index => $line) {
    if ($line == '') {
        continue;
    }

    $parts = explode(';', $line);

    if (count($parts) != 2) {
        die(sprintf('Expected 2 parts, got %d on line %d', count($parts), $index + 1));
    }

    list ($name, $code) = $parts;

    if ($index == 0) {
        if ($name != 'Country Name') {
            die(sprintf('Unexpected first line: %s', $line));
        }

        continue;
    }

    if (isset($countries[$code])) {
        die("Duplicate country code $code\n");
    }

    $countries[$code] = [$code, $name];

    if (isset($countryNameToCode[$name])) {
        die("Duplicate country name $name\n");
    }

    $countryNameToCode[$name] = $code;
}

/**
 * Countries from country-to-currency.php to ignore.
 */
$ignoreCountries = [
    'EUROPEAN UNION',
    'INTERNATIONAL MONETARY FUND (IMF)',
    'MEMBER COUNTRIES OF THE AFRICAN DEVELOPMENT BANK GROUP',
    'SISTEMA UNITARIO DE COMPENSACION REGIONAL DE PAGOS "SUCRE"',
    'Vatican City State (HOLY SEE)' // repeated twice, once as the official ISO 3166 name, once as here
];

/**
 * Maps country names from country-to-currency.php to ISO 3166 names.
 */
$countryMap = [
    'CONGO, THE DEMOCRATIC REPUBLIC OF' => 'CONGO, THE DEMOCRATIC REPUBLIC OF THE',
    'HEARD ISLAND AND McDONALD ISLANDS' => 'HEARD ISLAND AND MCDONALD ISLANDS',
    'KOREA, DEMOCRATIC PEOPLE’S REPUBLIC OF' => 'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF',
    'LAO PEOPLE’S DEMOCRATIC REPUBLIC' => 'LAO PEOPLE\'S DEMOCRATIC REPUBLIC',
    'VIRGIN ISLANDS (BRITISH)' => 'VIRGIN ISLANDS, BRITISH',
    'VIRGIN ISLANDS (US)' => 'VIRGIN ISLANDS, U.S.'
];

/**
 * Explicitly maps the countries that have multiple currencies to their main currency.
 */
$countryToUniqueCurrency = [
    'BT' => 'BTN',
    'BO' => 'BOB',
    'CL' => 'CLP',
    'CO' => 'COP',
    'CU' => 'CUP',
    'SV' => 'USD',
    'HT' => 'HTG',
    'LS' => 'LSL',
    'MX' => 'MXN',
    'NA' => 'NAD',
    'PA' => 'PAB',
    'CH' => 'CHF',
    'UY' => 'UYU',
    'US' => 'USD'
];

foreach ($countryToCurrency as $country => $currencies) {
    $country = str_replace("\xC2\xA0", ' ', $country); // UTF-8 non-breaking space is present at the end of some country names
    $country = trim($country);

    /**
     * Ignore all entries starting with ZZ.
     */
    if (substr($country, 0, 2) == 'ZZ') {
        continue;
    }

    if (in_array($country, $ignoreCountries)) {
        continue;
    }

    if (isset($countryMap[$country])) {
        $country = $countryMap[$country];
    }

    if (! isset($countryNameToCode[$country])) {
        die('Unknown country: ' . var_export($country, true) . "\n");
    }

    $countryCode = $countryNameToCode[$country];

    if (count($currencies) == 0) {
        $currency = null;
    }
    elseif (count($currencies) == 1) {
        list ($currency) = $currencies;
    } else {
        if (! isset($countryToUniqueCurrency[$countryCode])) {
            die("Expected 1 currency for $countryCode, got " . count($currencies));
        }

        $currency = $countryToUniqueCurrency[$countryCode];

        if (! in_array($currency, $currencies)) {
            die("$currency is not a valid currency for $countryCode");
        }
    }

    if (count($currencies) < 2) {
        if (isset($countryToUniqueCurrency[$countryCode])) {
            die("did not expect to find an entry in countryToUniqueCurrency for $countryCode");
        }
    }

    $countries[$countryCode][] = $currency;
}

foreach ($countries as $code => $country) {
    if (count($country) != 3) {
        die("Expected 3 entries (code, name, currency) for country $code, got " . count($country));
    }
}

file_put_contents('countries.php', sprintf("<?php return %s;\n", var_export($countries, true)));

printf('Successfully written %d countries' . PHP_EOL, count($countries));
