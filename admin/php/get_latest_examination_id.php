<?php

// Include the database connection (which uses PDO)
include('../connection/conn.php');

// Fetch the latest examination ID
try {
    // Prepare and execute the query
    $query = "SELECT MAX(id) AS latest_id FROM examinations";
    $stmt = $pdo->query($query);

    // Check if the query was successful
    if ($stmt) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $latestExaminationID = $row['latest_id'] ? $row['latest_id'] : 'N/A';
    } else {
        // If query fails
        $latestExaminationID = 'N/A'; // Or set a default value
    }
} catch (PDOException $e) {
    // Handle query errors
    echo "Error executing query: " . $e->getMessage();
    exit;
}
?>
