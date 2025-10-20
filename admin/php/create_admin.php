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
    $username = htmlspecialchars($_POST['username'] ?? '');
    $password = htmlspecialchars($_POST['password'] ?? '');
    $email    = htmlspecialchars($_POST['email'] ?? '');
    $role     = htmlspecialchars($_POST['role'] ?? '');
    
    // Default status to "Active" if not provided
    $status   = htmlspecialchars($_POST['status'] ?? 'Active');

    // Validate required fields
    if (empty($username) || empty($password) || empty($email) || empty($role)) {
        die("All fields are required.");
    }

    // Hash the password before inserting into the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert new admin
    $sql = "INSERT INTO admin (username, password, email, role, status) 
            VALUES (:username, :password, :email, :role, :status)";

    try {
        // Prepare and execute the query
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':status', $status);

        // Execute the query
        $stmt->execute();

        // Redirect to the admin list page after successful insert
        header('Location: ../admins.php');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
