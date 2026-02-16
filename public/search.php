<?php
/**
 * Unified search: diesel shops by ZIP code, city+state, or lat/lng.
 * Uses zips and cities_zip tables. Fallback: FDR_IMPORT.
 * POST: myZip + myDist  OR  city + state + myDist  OR  mylat + mylng + myDist
 * Returns JSON: { "results": [...], "query": {...}, "error": null|string }
 */
require_once __DIR__ . '/../lib/bootstrap.php';
require_once __DIR__ . '/../../lib/repositories/ZipRepository.php';
require_once __DIR__ . '/../lib/repositories/RepairFacilityRepository.php';

header('Content-Type: application/json; charset=utf-8');

$input = array_merge($_GET, $_POST);
$radius = isset($input['radius']) ? (int) $input['radius'] : (isset($input['myDist']) ? (int) $input['myDist'] : 50);
$radius = max(10, min(200, $radius));

$lat = null;
$lng = null;
$zip = null;
$queryType = null;

$conn = Database::getMysqli();
if (!$conn) {
    $detail = Database::$lastError ?: 'Check .env (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)';
    echo json_encode(['results' => [], 'query' => [], 'error' => 'Database unavailable: ' . $detail]);
    exit;
}

$zipRepo = new ZipRepository($conn);

if (!empty($input['zip']) || !empty($input['myZip'])) {
    $zipRaw = $input['zip'] ?? $input['myZip'];
    $zip = preg_replace('/[^0-9]/', '', trim((string) $zipRaw));
    $zip = strlen($zip) >= 5 ? substr($zip, 0, 5) : null;
    if ($zip) {
        $ll = $zipRepo->getLatLngByZip($zip);
        if (!$ll) {
            $res = $conn->query("SELECT latitude AS lat, longitude AS lng FROM FDR_IMPORT WHERE ZIPCODE = '" . $conn->real_escape_string($zip) . "' AND latitude IS NOT NULL AND longitude IS NOT NULL LIMIT 1");
            if ($res && $row = $res->fetch_assoc()) {
                $ll = ['lat' => (float) $row['lat'], 'lng' => (float) $row['lng']];
            }
        }
        if ($ll) {
            $lat = $ll['lat'];
            $lng = $ll['lng'];
            $queryType = 'zip';
        }
    }
} elseif (!empty(trim((string) ($input['city'] ?? '')))) {
    $city = strtoupper(trim((string) $input['city']));
    $stateRaw = trim((string) ($input['state'] ?? ''));
    $state = $stateRaw !== '' ? (ZipRepository::stateNameToAbbr($stateRaw) ?? (strlen($stateRaw) === 2 ? strtoupper($stateRaw) : null)) : null;
    $ll = $zipRepo->getLatLngByCityState($city, $state ?? '');
    if (!$ll && $state !== null && $state !== '') {
        $cityEsc = $conn->real_escape_string($city);
        $stateEsc = $conn->real_escape_string($state);
        $res = $conn->query("SELECT latitude AS lat, longitude AS lng FROM FDR_IMPORT WHERE UPPER(TRIM(CITY)) = '" . $cityEsc . "' AND UPPER(TRIM(STATE)) = '" . $stateEsc . "' AND latitude IS NOT NULL AND longitude IS NOT NULL LIMIT 1");
        if ($res && $row = $res->fetch_assoc()) {
            $ll = ['lat' => (float) $row['lat'], 'lng' => (float) $row['lng']];
        }
    }
    if ($ll) {
        $lat = $ll['lat'];
        $lng = $ll['lng'];
        $queryType = 'city';
    }
} elseif (isset($input['lat'], $input['lng']) || isset($input['mylat'], $input['mylng'])) {
    $lat = (float) ($input['lat'] ?? $input['mylat']);
    $lng = (float) ($input['lng'] ?? $input['mylng']);
    if ($lat !== 0.0 || $lng !== 0.0) {
        $queryType = 'location';
    }
}

$response = [
    'results' => [],
    'query'   => ['type' => $queryType, 'zip' => $zip, 'radius' => $radius],
    'error'   => null,
];

if ($lat === null || $lng === null) {
    if ($zip !== null) {
        $response['error'] = "We couldn't find coordinates for that ZIP code. Please check the number and try again.";
    } elseif (!empty(trim((string) ($input['city'] ?? '')))) {
        $response['error'] = "We couldn't find that city. Try a different spelling or use \"city, state\" (e.g. Phoenix, AZ).";
    } else {
        $response['error'] = 'Please provide a ZIP code, city and state, or your location (lat/lng).';
    }
    echo json_encode($response);
    exit;
}

$repo = new RepairFacilityRepository($conn);
$response['results'] = $repo->findNear($lat, $lng, $radius);

echo json_encode($response);
