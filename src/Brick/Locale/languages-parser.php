<?php

$url = 'http://www.loc.gov/standards/iso639-2/ISO-639-2_utf-8.txt';
$data = file_get_contents($url);

if (substr($data, 0, 3) === "\xef\xbb\xbf") { // BOM
    $data = substr($data, 3);
}

$lines = preg_split('/[\r\n]+/', $data);

$languages = array();
$alpha2to3 = array();
$alphaTtoB = array();

foreach ($lines as $line) {
    if ($line == '') {
        continue;
    }

    $parts = explode('|', $line);

    if (count($parts) != 5) {
        die("Unexpected line format: $line\n");
    }

    list ($alpha3B, $alpha3T, $alpha2, $englishName, $frenchName) = $parts;

    if (preg_match('/^[a-z]{3}\-[a-z]{3}$/', $alpha3B) != 0) {
        continue; // Skip ranges
    }

    if (preg_match('/^[a-z]{3}$/', $alpha3B) == 0) {
        die("Invalid alpha3B code: $alpha3B\n");
    }

    if ($englishName == '') {
        die("Empty English name for code $alpha3B\n");
    }

    if ($frenchName == '') {
        die("Empty French name for code $alpha3B\n");
    }

    if ($alpha2 == '') {
        $alpha2 = null;
    } else {
        if (preg_match('/[a-z]{2}$/', $alpha2) == 0) {
            die("Invalid alpha2 code: $alpha2\n");
        }

        $alpha2to3[$alpha2] = $alpha3B;
    }

    if ($alpha3T == '') {
        $alpha3T = null;
    } else {
        if (preg_match('/^[a-z]{3}$/', $alpha3T) == 0) {
            die("Invalid alpha3T code: $alpha3T\n");
        }


    }

    if (isset($languages[$alpha3B])) {
        die("Duplicate alpha3B code: $alpha3B\n");
    }



    $languages[$alpha3B] = array($alpha3B, $alpha3T, $alpha2, $englishName, $frenchName);
}

exportToFile('languages.php', $languages);
exportToFile('languages-2-to-3.php', $alpha2to3);
exportToFile('languages-T-to-B.php', $alphaTtoB);

printf('Successfully exported %d languages and %d alpha-2 mappings' . PHP_EOL, count($languages), count($alpha2to3));

/**
 * @param string $file
 * @param mixed  $data
 *
 * @return void
 */
function exportToFile($file, $data)
{
    file_put_contents($file, sprintf("<?php return %s;\n", var_export($data, true)));
}
