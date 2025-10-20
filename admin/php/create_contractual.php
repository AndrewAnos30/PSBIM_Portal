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
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $examination_id = htmlspecialchars($_POST['examination_id']);
    $room_number = htmlspecialchars($_POST['room_number']);

    // Validate required fields
    if (empty($username) || empty($password) || empty($examination_id) || empty($room_number)) {
        die("All required fields must be filled out.");
    }

    // Hash the password before inserting into the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert new contractual user
    $sql = "INSERT INTO contractual (username, password, examination_id, room_number, created_at) 
            VALUES (:username, :password, :examination_id, :room_number, NOW())";

    try {
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':examination_id', $examination_id);
        $stmt->bindParam(':room_number', $room_number);

        // Execute the query
        $stmt->execute();

        // Redirect after successful insert
        header('Location: ../admin_dashboard.php');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // If accessed directly without POST
    header('Location: ../admins.php');
    exit();
}
?>
