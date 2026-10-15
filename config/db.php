<?php
/**
 * Database Configuration & Connection Utility
 * Supports MySQL / MariaDB via PDO with prepared statement enforcement
 * Compatible with Local XAMPP, Render.com, Docker, and Cloud Database Providers
 */

declare(strict_types=1);

// Detect cloud database URLs (e.g. Render, Railway, PlanetScale: mysql://user:pass@host:port/dbname)
$cloudDbUrl = getenv('DATABASE_URL') ?: getenv('MYSQL_URL') ?: null;

$dbHost = 'localhost';
$dbPort = '3306';
$dbName = 'portfolio_db';
$dbUser = 'root';
$dbPass = '';

if (!empty($cloudDbUrl)) {
    $parsed = parse_url($cloudDbUrl);
    if ($parsed !== false) {
        $dbHost = $parsed['host'] ?? 'localhost';
        $dbPort = isset($parsed['port']) ? (string)$parsed['port'] : '3306';
        $dbUser = $parsed['user'] ?? 'root';
        $dbPass = $parsed['pass'] ?? '';
        $dbName = isset($parsed['path']) ? ltrim($parsed['path'], '/') : 'portfolio_db';
    }
} else {
    $dbHost = getenv('DB_HOST') ?: 'localhost';
    $dbPort = getenv('DB_PORT') ?: '3306';
    $dbName = getenv('DB_NAME') ?: 'portfolio_db';
    $dbUser = getenv('DB_USER') ?: 'root';
    $dbPass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
}

define('DB_HOST', $dbHost);
define('DB_PORT', $dbPort);
define('DB_NAME', $dbName);
define('DB_USER', $dbUser);
define('DB_PASS', $dbPass);
define('DB_CHARSET', 'utf8mb4');

/**
 * Returns a shared PDO instance or null if connection cannot be established
 * 
 * @return PDO|null
 */
function getDBConnection(): ?PDO
{
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', DB_HOST, DB_PORT, DB_NAME, DB_CHARSET);

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false, // Crucial for true SQL injection defense
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
        PDO::ATTR_TIMEOUT            => 3,
    ];

    // If cloud provider requires SSL without verification (e.g. Aiven, PlanetScale, TiDB)
    if (getenv('MYSQL_SSL_DISABLED') === 'false' || getenv('MYSQL_ATTR_SSL_CA')) {
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
    }

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        // Fallback gracefully without breaking public portfolio
        error_log('[DB CONNECTION NOTICE] Running in offline/fallback mode: ' . $e->getMessage());
        return null;
    }
}

/**
 * Checks if database is reachable and initialized
 * 
 * @return bool
 */
function isDatabaseConnected(): bool
{
    return getDBConnection() !== null;
}
