<?php
/**
 * Database Configuration Template
 * 
 * SETUP INSTRUCTIONS:
 * 1. Copy this file to 'db-config.php'
 * 2. Fill in your actual database credentials below
 * 3. Never commit db-config.php to Git (it's in .gitignore)
 */

// Database credentials - REPLACE THESE WITH YOUR ACTUAL VALUES
define('DB_HOST', 'localhost');              // Usually 'localhost' or '127.0.0.1'
define('DB_NAME', 'your_database_name');     // Your database name from Plesk
define('DB_USER', 'your_database_username'); // Your database username from Plesk
define('DB_PASS', 'your_database_password'); // Your database password
define('DB_CHARSET', 'utf8mb4');

// Error reporting - SET TO FALSE IN PRODUCTION
define('DEBUG_MODE', false);

// Create database connection
function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
        
    } catch (PDOException $e) {
        if (DEBUG_MODE) {
            die("Database connection failed: " . $e->getMessage());
        } else {
            // Log error to file instead of displaying
            error_log("DB Connection Error: " . $e->getMessage());
            die("Database connection failed. Please contact support.");
        }
    }
}
?>