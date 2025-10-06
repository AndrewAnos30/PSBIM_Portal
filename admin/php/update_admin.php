<?php
// Include the database connection
include('../../connection/conn.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the exam ID and other form data
    $exam_id  = isset($_POST['old_id']) ? intval($_POST['old_id']) : 0;
    $title    = trim($_POST['title'] ?? '');
    $date     = trim($_POST['date'] ?? '');
    $time     = trim($_POST['time'] ?? '');
    $location = trim($_POST['location'] ?? '');

    // Validate input
    if ($exam_id <= 0) {
        die("Invalid Examination ID.");
    }
    if (empty($title) || empty($date) || empty($time) || empty($location)) {
        die("All fields are required.");
    }

    try {
        // Update the examinations table
        $sql = "UPDATE examinations SET 
                    title = :title,
                    date = :date,
                    time = :time,
                    location = :location
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':time', $time, PDO::PARAM_STR);
        $stmt->bindParam(':location', $location, PDO::PARAM_STR);
        $stmt->bindParam(':id', $exam_id, PDO::PARAM_INT);
        $stmt->execute();

        // Redirect back to exams list
        header("Location: ../exams.php");
        exit;

    } catch (PDOException $e) {
        echo "Error updating examination: " . $e->getMessage();
    }
}
?>
