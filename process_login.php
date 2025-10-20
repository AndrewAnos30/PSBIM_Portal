<?php
session_start();
require_once 'connection/conn.php'; // Secure PDO connection

// Strengthen session settings (especially for production)
ini_set('session.cookie_secure', 1);   // Require HTTPS for cookies
ini_set('session.cookie_httponly', 1); // Prevent JavaScript access
ini_set('session.use_strict_mode', 1); // Reject uninitialized sessions

// Initialize or check login attempt counter
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

// If user exceeded max attempts, block temporarily
if ($_SESSION['login_attempts'] >= 5) {
    $_SESSION['error'] = "Too many failed login attempts. Please try again later.";
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    // Sanitize and validate input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Please enter both username and password.";
        header('Location: index.php');
        exit;
    }

    try {
        // Prepare secure query
        $stmt = $pdo->prepare("SELECT id, username, password FROM members WHERE username = :username LIMIT 1");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            // Reset attempts on success
            $_SESSION['login_attempts'] = 0;

            // Regenerate session ID to prevent fixation
            session_regenerate_id(true);

            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['last_login'] = time();

            header('Location: member/member_dashboard.php');
            exit;
        } else {
            // Increment failed attempts
            $_SESSION['login_attempts']++;

            $_SESSION['error'] = "Invalid username or password. Attempt {$_SESSION['login_attempts']} of 5.";
            header('Location: index.php');
            exit;
        }
    } catch (PDOException $e) {
        // Log error securely (not shown to user)
        error_log("Login error: " . $e->getMessage());
        $_SESSION['error'] = "An unexpected error occurred. Please try again.";
        header('Location: index.php');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?>
