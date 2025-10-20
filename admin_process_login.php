<?php
session_start();
require_once 'connection/conn.php'; // Secure PDO connection

// Strengthen session settings
ini_set('session.cookie_secure', 1);   // Require HTTPS
ini_set('session.cookie_httponly', 1); // Prevent JS access
ini_set('session.use_strict_mode', 1); // Reject uninitialized sessions

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Please enter both username and password.";
        header('Location: admin-login.php');
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT id, username, password FROM admin WHERE username = :username LIMIT 1");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);

            $_SESSION['admin_id'] = $user['id'];       // ✅ Changed to match access file
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['last_login'] = time();

            header('Location: admin/members.php');
            exit;
        } else {
            $_SESSION['error'] = "Invalid username or password.";
            header('Location: admin-login.php');
            exit;
        }
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        $_SESSION['error'] = "An unexpected error occurred. Please try again.";
        header('Location: admin-login.php');
        exit;
    }
} else {
    header('Location: admin-login.php');
    exit;
}
?>
