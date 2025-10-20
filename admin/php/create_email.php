<?php
session_start();

// ✅ Restrict access to logged-in admins only
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../admin-login.php"); // Go two folders up to reach admin-login.php
    exit;
}

// Include the database connection
include('../../connection/conn.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize form data
    $subject        = htmlspecialchars($_POST['subject']);
    $examination_id = htmlspecialchars($_POST['examination_id']);

    // Validate required fields
    if (empty($subject) || empty($examination_id)) {
        die("All fields are required.");
    }

    // SQL query to insert new email record
    $sql = "INSERT INTO emails (subject, examination_id) 
            VALUES (:subject, :examination_id)";

    try {
        // Prepare and execute the query
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':examination_id', $examination_id);

        // Execute the query
        $stmt->execute();

        // Redirect to the emails list page after successful insert
        header('Location: ../emails.php');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
