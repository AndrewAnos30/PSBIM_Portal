<?php
/**
 * logout.php — Secure session termination
 * Destroys all session data and redirects safely to the login page
 */

// Disable error display to avoid header leaks
ini_set('display_errors', 0);
error_reporting(0);

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Prevent logout if no session exists (optional safety)
if (empty($_SESSION)) {
    header('Location: ../index.php');
    exit;
}

// Unset all session variables
$_SESSION = [];

// Regenerate session ID before destroying to prevent fixation
session_regenerate_id(true);

// Destroy the session completely
if (session_id() !== '' || isset($_COOKIE[session_name()])) {
    // Remove session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
}

// Finally, destroy the session
session_destroy();

// Double-check: remove PHPSESSID from URL if present
if (ini_get("session.use_trans_sid")) {
    ini_set("session.use_trans_sid", 0);
}

// Redirect to your login page (index.php)
header('Location: ../index.php');
exit;
?>
