<?php
// Include DB connection
include('../../connection/conn.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize form data
    $examination_id = htmlspecialchars($_POST['examination_id']);
    $title = htmlspecialchars($_POST['title']);
    $date = htmlspecialchars($_POST['date']);
    $time = htmlspecialchars($_POST['time']);
    $location = htmlspecialchars($_POST['location']);

    // Validate required fields
    if (empty($examination_id) || empty($title) || empty($date) || empty($time) || empty($location)) {
        die("All fields are required.");
    }

    // SQL query
    $sql = "INSERT INTO examinations (id, title, date, time, location)
            VALUES (:id, :title, :date, :time, :location)";

    try {
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':id', $examination_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':location', $location);

        // Execute query
        $stmt->execute();

        // Redirect back to examinations page
        header('Location: ../examinations.php');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
