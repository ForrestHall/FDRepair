<?php
/**
 * Seed finddieselrepair database from ATWRF.csv
 *
 * Usage: php scripts/seed-db.php [path-to-ATWRF.csv]
 * Run from project root.
 *
 * Expected CSV columns (case-insensitive; first row = header):
 *   NAME, ADDRESS, CITY, STATE, ZIPCODE, PHONE, SITE, MAP, RATE, MOBILE, VERIFIED, latitude, longitude, PLACE
 */
$projectRoot = dirname(__DIR__);
$csvPath = $argv[1] ?? $projectRoot . '/data/ATWRF.csv';
$csvPath = realpath($csvPath) ?: (is_file($csvPath) ? $csvPath : $projectRoot . '/data/ATWRF.csv');

if (!is_readable($csvPath)) {
    fwrite(STDERR, "Error: Cannot read CSV: {$csvPath}\n");
    fwrite(STDERR, "Usage: php scripts/seed-db.php [path-to-ATWRF.csv]\n");
    exit(1);
}

require_once $projectRoot . '/lib/bootstrap.php';
require_once dirname($projectRoot) . '/lib/repositories/ZipRepository.php';

$conn = Database::getMysqli();
if (!$conn) {
    fwrite(STDERR, "Error: Database connection failed. Check .env (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME).\n");
    exit(1);
}

$handle = fopen($csvPath, 'r');
if (!$handle) {
    fwrite(STDERR, "Error: Could not open CSV\n");
    exit(1);
}

$header = fgetcsv($handle);
if (!$header) {
    fwrite(STDERR, "Error: Empty CSV or no header\n");
    fclose($handle);
    exit(1);
}

$header = array_map('trim', $header);
$colMap = [];
$wanted = ['NAME','ADDRESS','CITY','STATE','ZIPCODE','PHONE','SITE','MAP','RATE','MOBILE','VERIFIED','latitude','longitude','PLACE'];
$aliases = [
    'name' => 'NAME', 'address' => 'ADDRESS', 'city' => 'CITY', 'state' => 'STATE',
    'zip' => 'ZIPCODE', 'zipcode' => 'ZIPCODE', 'phone' => 'PHONE', 'website' => 'SITE', 'site' => 'SITE', 'url' => 'SITE',
    'map' => 'MAP', 'rate' => 'RATE', 'rating' => 'RATE', 'mobile' => 'MOBILE', 'verified' => 'VERIFIED',
    'lat' => 'latitude', 'lng' => 'longitude', 'longitude' => 'longitude', 'place' => 'PLACE', 'place_id' => 'PLACE'
];
foreach ($header as $i => $key) {
    $key = trim($key);
    $k = $aliases[strtolower($key)] ?? $key;
    if (in_array($k, $wanted) || in_array($key, $wanted)) {
        $colMap[$k] = $i;
    }
}
if (empty($colMap['NAME'])) {
    $colMap['NAME'] = array_search('NAME', $header) !== false ? array_search('NAME', $header) : 0;
}

$insertListing = $conn->prepare(
    "INSERT INTO FDR_IMPORT (NAME, ADDRESS, CITY, STATE, ZIPCODE, PHONE, SITE, MAP, RATE, MOBILE, VERIFIED, latitude, longitude, PLACE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
);
$insertListingNoGeo = $conn->prepare(
    "INSERT INTO FDR_IMPORT (NAME, ADDRESS, CITY, STATE, ZIPCODE, PHONE, SITE, MAP, RATE, MOBILE, VERIFIED, PLACE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
);
$insertZip = $conn->prepare("INSERT INTO zips (ZIP, LAT, LNG) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE LAT = VALUES(LAT), LNG = VALUES(LNG)");
$insertCityZip = $conn->prepare("INSERT INTO cities_zip (CITY, STATE, LAT, LNG, ZIP) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE LAT = VALUES(LAT), LNG = VALUES(LNG), ZIP = VALUES(ZIP)");

$listingsInserted = 0;
$zipsSeen = [];
$citiesSeen = [];

while (($row = fgetcsv($handle)) !== false) {
    if (count($row) < 2) continue;
    $get = function($col) use ($row, $colMap) {
        $i = $colMap[$col] ?? null;
        return $i !== null && isset($row[$i]) ? trim($row[$i]) : null;
    };
    $name = $get('NAME') ?: $row[0];
    if ($name === '') continue;
    $address = $get('ADDRESS');
    $city = $get('CITY');
    $state = $get('STATE');
    $zipcode = $get('ZIPCODE');
    $phone = $get('PHONE');
    $site = $get('SITE');
    $map = $get('MAP');
    $rate = $get('RATE');
    $mobile = (int) (preg_match('/^(1|true|yes|y)$/i', trim($get('MOBILE') ?? '0')) ? 1 : 0);
    $verified = (int) (preg_match('/^(1|true|yes|y)$/i', trim($get('VERIFIED') ?? '0')) ? 1 : 0);
    $latRaw = $get('latitude');
    $lngRaw = $get('longitude');
    $lat = ($latRaw !== null && $latRaw !== '') ? (float) $latRaw : null;
    $lng = ($lngRaw !== null && $lngRaw !== '') ? (float) $lngRaw : null;
    $place = $get('PLACE');
    if ($lat !== null && $lng !== null) {
        $insertListing->bind_param('sssssssssiidds', $name, $address, $city, $state, $zipcode, $phone, $site, $map, $rate, $mobile, $verified, $lat, $lng, $place);
        $insertListing->execute();
    } else {
        $insertListingNoGeo->bind_param('sssssssssiis', $name, $address, $city, $state, $zipcode, $phone, $site, $map, $rate, $mobile, $verified, $place);
        $insertListingNoGeo->execute();
    }
    $listingsInserted++;
    if ($zipcode !== null && $zipcode !== '' && $lat !== null && $lng !== null && !isset($zipsSeen[$zipcode])) {
        $zipsSeen[$zipcode] = true;
        $insertZip->bind_param('sdd', $zipcode, $lat, $lng);
        $insertZip->execute();
    }
    if ($city !== null && $city !== '' && $state !== null && $state !== '' && $lat !== null && $lng !== null) {
        $ck = strtoupper(trim($city)) . '|' . strtoupper(trim($state));
        if (!isset($citiesSeen[$ck])) {
            $citiesSeen[$ck] = true;
            $cu = strtoupper(trim($city));
            $su = strlen(trim($state)) === 2 ? strtoupper(trim($state)) : (ZipRepository::stateNameToAbbr($state) ?? strtoupper(trim($state)));
            if ($su !== '') {
                $insertCityZip->bind_param('ssdds', $cu, $su, $lat, $lng, $zipcode ?? '');
                $insertCityZip->execute();
            }
        }
    }
}
fclose($handle);
$insertListing->close();
$insertListingNoGeo->close();
$insertZip->close();
$insertCityZip->close();

echo "Seeded {$listingsInserted} listings from " . basename($csvPath) . ".\n";
echo "ZIP codes in zips: " . count($zipsSeen) . ".\n";
echo "City/state in cities_zip: " . count($citiesSeen) . ".\n";
