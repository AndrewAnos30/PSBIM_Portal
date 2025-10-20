<?php
session_start();
require_once 'connection/conn.php'; // Secure PDO connection

// === Secure session configuration ===
ini_set('session.cookie_secure', 1);    // Require HTTPS
ini_set('session.cookie_httponly', 1);  // Prevent JS access
ini_set('session.use_strict_mode', 1);  // Reject uninitialized sessions

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // === Validate inputs ===
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Please enter both username and password.";
        header('Location: contractual/contractual-login.php');
        exit;
    }

    try {
        // === Fetch contractual account ===
        $stmt = $pdo->prepare("
            SELECT 
                id, 
                username, 
                password, 
                examination_id, 
                room_number
            FROM contractual
            WHERE username = :username
            LIMIT 1
        ");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // === Verify credentials ===
        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID to prevent fixation
            session_regenerate_id(true);

            // ✅ Store essential user data in session
            $_SESSION['contractual_id']        = $user['id'];
            $_SESSION['contractual_username']  = $user['username'];
            $_SESSION['examination_id']        = $user['examination_id'];
            $_SESSION['room_number']           = $user['room_number'];
            $_SESSION['last_login']            = time();

            // Redirect to contractual dashboard
            header('Location: contractual/examinees.php');
            exit;
        } else {
            $_SESSION['error'] = "Invalid username or password.";
            header('Location: contractual/contractual-login.php');
            exit;
        }

    } catch (PDOException $e) {
        // Log internal error
        error_log("Contractual login error: " . $e->getMessage());

        $_SESSION['error'] = "An unexpected error occurred. Please try again later.";
        header('Location: contractual/contractual-login.php');
        exit;
    }

} else {
    // Redirect non-POST access
    header('Location: contractual/contractual-login.php');
    exit;
}
?>
