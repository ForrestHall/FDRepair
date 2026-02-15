<?php
/**
 * Database connection wrapper. Use after bootstrap.
 */
class Database {
    private static ?PDO $pdo = null;
    private static ?mysqli $mysqli = null;
    public static ?string $lastError = null;

    public static function getPdo(): ?PDO {
        if (self::$pdo !== null) return self::$pdo;
        $db = config('db');
        if (empty($db['host']) || $db['user'] === '') return null;
        try {
            self::$pdo = new PDO(
                "mysql:host={$db['host']};dbname=" . ($db['database'] ?? 'finddieselrepair') . ";charset=utf8mb4",
                $db['user'],
                $db['password'] ?? '',
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
            );
        } catch (PDOException $e) {
            return null;
        }
        return self::$pdo;
    }

    public static function getMysqli(): ?mysqli {
        if (self::$mysqli !== null) return self::$mysqli;
        $db = config('db');
        self::$lastError = null;
        if (empty($db['host']) || $db['user'] === '') {
            self::$lastError = 'DB config missing (set DB_HOST, DB_USER, DB_PASSWORD, DB_NAME in .env)';
            return null;
        }
        $conn = new mysqli(
            $db['host'],
            $db['user'],
            $db['password'] ?? '',
            $db['database'] ?? 'finddieselrepair'
        );
        if ($conn->connect_error) {
            self::$lastError = $conn->connect_error;
            return null;
        }
        $conn->set_charset('utf8mb4');
        self::$mysqli = $conn;
        return self::$mysqli;
    }
}
