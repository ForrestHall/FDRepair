<?php
/**
 * Shared bootstrap: load .env, autoload, config.
 * Include from project root: require_once __DIR__ . '/lib/bootstrap.php';
 */
$projectRoot = getenv('PROJECT_ROOT') ?: dirname(__DIR__);

// Load .env from first path that exists
$envPaths = array_filter([
    getenv('ENV_FILE') ?: null,
    $projectRoot . '/.env',
    isset($_SERVER['DOCUMENT_ROOT']) ? rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/../.env' : null,
    isset($_SERVER['DOCUMENT_ROOT']) ? rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/.env' : null,
]);
$envFile = null;
foreach ($envPaths as $p) {
    if ($p && file_exists($p) && is_readable($p)) {
        $envFile = $p;
        break;
    }
}
$GLOBALS['_ENV_FILE_LOADED'] = $envFile;
$GLOBALS['_ENV_PATHS_TRIED'] = array_values($envPaths);
if ($envFile) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($key, $val) = explode('=', $line, 2);
            $key = trim($key);
            $val = trim($val, " \t\n\r\0\x0B\"'");
            if ($key !== '') {
                $_ENV[$key] = $val;
                if (function_exists('putenv')) {
                    @putenv("$key=$val");
                }
            }
        }
    }
}

$e = fn($k, $d = '') => $_ENV[$k] ?? getenv($k) ?: $d;

// Build config from .env (config.php override when Get Found is added)
$configPath = $projectRoot . '/public/api/get-found/config.php';
$_CONFIG = [];
if (file_exists($configPath)) {
    $_CONFIG = require $configPath;
    if (!is_array($_CONFIG)) $_CONFIG = [];
}
if (empty($_CONFIG['db']['host']) && $e('DB_HOST')) {
    $_CONFIG['db'] = [
        'host'     => $e('DB_HOST'),
        'user'     => $e('DB_USER'),
        'password' => $e('DB_PASSWORD'),
        'database' => $e('DB_NAME', 'finddieselrepair'),
    ];
}
if (empty($_CONFIG['base_url'])) {
    $_CONFIG['base_url'] = $e('BASE_URL', 'https://finddieselrepair.com');
}
if (empty($_CONFIG['site_name'])) {
    $_CONFIG['site_name'] = $e('SITE_NAME', 'Find Diesel Repair');
}
if (empty($_CONFIG['contact_email']) && $e('CONTACT_EMAIL')) {
    $_CONFIG['contact_email'] = $e('CONTACT_EMAIL');
}

// Composer autoload
if (file_exists($projectRoot . '/vendor/autoload.php')) {
    require_once $projectRoot . '/vendor/autoload.php';
}

function config(?string $key = null, $default = null) {
    global $_CONFIG;
    if ($key === null) return $_CONFIG;
    $keys = explode('.', $key);
    $v = $_CONFIG;
    foreach ($keys as $k) {
        $v = $v[$k] ?? null;
        if ($v === null) return $default;
    }
    return $v;
}

function env(string $key, string $default = ''): string {
    return (string) ($_ENV[$key] ?? getenv($key) ?: $default);
}

require_once __DIR__ . '/Database.php';
