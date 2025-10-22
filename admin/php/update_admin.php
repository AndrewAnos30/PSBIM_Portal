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
    // Get and sanitize form data
    $id       = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $email    = trim($_POST['email'] ?? '');
    $role     = trim($_POST['role'] ?? '');
    $status   = trim($_POST['status'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validate inputs
    if ($id <= 0) {
        die("Invalid admin ID.");
    }
    if (empty($email) || empty($role) || empty($status)) {
        die("Email, role, and status are required.");
    }

    try {
        // If password is provided, update it too
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE admin 
                    SET email = :email, role = :role, status = :status, password = :password 
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        } else {
            // Update everything except password
            $sql = "UPDATE admin 
                    SET email = :email, role = :role, status = :status 
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
        }

        // Bind common parameters
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Execute update
        $stmt->execute();

        // Redirect back to the admin list (or wherever your admin management page is)
        header("Location: ../admins.php");
        exit;

    } catch (PDOException $e) {
        echo "Error updating admin: " . $e->getMessage();
    }
}
?>
