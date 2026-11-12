<?php
// TEMPORARY: confirms db-config.php is found and the database works. DELETE AFTER USE.
require_once file_exists(__DIR__ . '/../db-config.php') ? __DIR__ . '/../db-config.php' : __DIR__ . '/db-config.php';
$n = getDBConnection()->query('SELECT COUNT(*) FROM registrations')->fetchColumn();
echo "OK, registrations: $n";
