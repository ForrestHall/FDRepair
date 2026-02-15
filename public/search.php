<?php
/**
 * Unified search: diesel shops by ZIP code or by lat/lng.
 * POST: myZip + myDist  OR  mylat + mylng + myDist
 * Returns JSON: { "results": [...], "query": {...}, "error": null|string }
 */
require_once __DIR__ . '/../lib/bootstrap.php';
require_once __DIR__ . '/../lib/geocode.php';
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
    echo json_encode(['results' => [], 'query' => [], 'error' => 'Database unavailable']);
    exit;
}

if (!empty($input['zip']) || !empty($input['myZip'])) {
    $zipRaw = $input['zip'] ?? $input['myZip'];
    $zip = preg_replace('/[^0-9]/', '', $zipRaw);
    $zip = strlen($zip) >= 5 ? substr($zip, 0, 5) : null;
    if ($zip) {
        $geo = fdr_geocode_zip($zip, $conn);
        if ($geo) {
            $lat = $geo[0];
            $lng = $geo[1];
            $queryType = 'zip';
        }
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
    } else {
        $response['error'] = 'Please provide a ZIP code or your location (lat/lng).';
    }
    echo json_encode($response);
    exit;
}

$repo = new RepairFacilityRepository($conn);
$response['results'] = $repo->findNear($lat, $lng, $radius);

echo json_encode($response);
