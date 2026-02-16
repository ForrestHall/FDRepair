<?php
/**
 * Diagnostic: .env loading and database connection.
 * Visit /api-check.php to see if .env is found and DB connects.
 * Remove or restrict in production.
 */
header('Content-Type: application/json; charset=utf-8');

$out = [
    'ok' => false,
    'env_loaded' => false,
    'db_connected' => false,
    'error' => null,
    'env_file' => null,
    'paths_tried' => [],
    'db_error' => null,
];

try {
    require_once __DIR__ . '/../lib/bootstrap.php';
    $out['env_file'] = $GLOBALS['_ENV_FILE_LOADED'] ?? null;
    $out['paths_tried'] = $GLOBALS['_ENV_PATHS_TRIED'] ?? [];
    $out['env_loaded'] = !empty($out['env_file']);

    $conn = Database::getMysqli();
    $out['db_connected'] = $conn !== null;
    $out['ok'] = $out['db_connected'];

    if (!$conn) {
        $out['error'] = 'Database connection failed';
        $out['db_error'] = Database::$lastError ?? 'No detail';
    }
} catch (Throwable $e) {
    $out['error'] = $e->getMessage();
}

echo json_encode($out, JSON_PRETTY_PRINT);
