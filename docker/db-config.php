<?php
/**
 * Local Docker database configuration (copy of db-config.template.php with the compose.yaml values).
 * Mounted above the web root at /var/www/db-config.php, as on the server. Local credentials only.
 */

define('DB_HOST', 'db');
define('DB_NAME', 'ibf');
define('DB_USER', 'ibf');
define('DB_PASS', 'ibf_local_password');
define('DB_CHARSET', 'utf8mb4');

// Same as the live site. Errors are written to the PHP log: docker compose logs php
define('DEBUG_MODE', false);

function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        if (DEBUG_MODE) {
            die("Database connection failed: " . $e->getMessage());
        }
        error_log("DB Connection Error: " . $e->getMessage());
        die("Database connection failed. Please contact support.");
    }
}
