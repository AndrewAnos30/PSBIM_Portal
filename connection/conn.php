<?php
// Database configuration
$host = 'localhost'; // Database host (default for XAMPP)
$dbname = 'psbim';   // Your database name (psbim)
$username = 'root';   // Default username for XAMPP MySQL
$password = '';       // Default password is empty for XAMPP

// DSN (Data Source Name) for PDO connection
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

try {
    // Create a PDO instance
    $pdo = new PDO($dsn, $username, $password);

    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optionally, you can log successful connections
    // echo "Connection successful!";
} catch (PDOException $e) {
    // Catch any exceptions and display the error message
    echo "Connection failed: " . $e->getMessage();
    exit; // Exit the script if the connection fails
}
?>
