<?php
/**
 * Get latitude/longitude for a US ZIP code.
 * Uses fdr_zips table first, then falls back to zippopotam.us API and caches result.
 *
 * @param string $zip 5-digit US ZIP
 * @param mysqli|null $conn Optional DB connection (for cache insert)
 * @return array|null [lat, lng, city?, state?] or null if not found
 */
function fdr_geocode_zip($zip, $conn = null) {
    $zip = preg_replace('/[^0-9]/', '', $zip);
    if (strlen($zip) < 5) return null;
    $zip = substr($zip, 0, 5);

    if ($conn) {
        $stmt = $conn->prepare("SELECT LAT, LNG, CITY, STATE FROM fdr_zips WHERE ZIP = ? LIMIT 1");
        $stmt->bind_param('s', $zip);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if ($row) {
            return [(float) $row['LAT'], (float) $row['LNG'], $row['CITY'] ?? null, $row['STATE'] ?? null];
        }
    }

    $url = 'https://api.zippopotam.us/us/' . $zip;
    $ctx = stream_context_create([
        'http' => ['timeout' => 5, 'user_agent' => 'FindDieselRepair/1.0'],
    ]);
    $json = @file_get_contents($url, false, $ctx);
    if ($json === false) return null;
    $data = json_decode($json, true);
    if (empty($data['places'][0])) return null;

    $place = $data['places'][0];
    $lat = (float) ($place['latitude'] ?? 0);
    $lng = (float) ($place['longitude'] ?? 0);
    $city = $place['place name'] ?? null;
    $state = ($data['country abbreviation'] ?? '') === 'US' && !empty($place['state abbreviation'])
        ? $place['state abbreviation']
        : ($place['state'] ?? null);
    if ($lat === 0.0 && $lng === 0.0) return null;

    if ($conn) {
        $ins = $conn->prepare("INSERT INTO fdr_zips (ZIP, LAT, LNG, CITY, STATE) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE LAT = VALUES(LAT), LNG = VALUES(LNG), CITY = VALUES(CITY), STATE = VALUES(STATE)");
        $ins->bind_param('sddss', $zip, $lat, $lng, $city, $state);
        $ins->execute();
        $ins->close();
    }

    return [$lat, $lng, $city, $state];
}
