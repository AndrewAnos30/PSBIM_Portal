<?php
// ====================================
// Secure Database Connection
// ====================================

// Database credentials
$host = 'localhost';
$dbname = 'psbim';
$username = 'root';
$password = '';

// PDO Data Source Name
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

// PDO options for better security and performance
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Use exceptions for errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Return associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use real prepared statements
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // ❌ Don't display error details to users
    // ✅ Log it to a server file instead
    error_log("[" . date('Y-m-d H:i:s') . "] DB Connection failed: " . $e->getMessage() . "\n", 3, __DIR__ . '/db_error.log');
    die('Database connection error.');
}
?>
