<?php
/**
 * admin-logout.php — Securely logs out an admin user
 * Completely destroys the admin session and redirects to admin-login.php
 */

// Disable error display to prevent header leaks
ini_set('display_errors', 0);
error_reporting(0);

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Optional: Verify it's an admin session
if (empty($_SESSION['admin_id'])) {
    // If no admin session exists, redirect safely
    header('Location: ../admin-login.php');
    exit;
}

// ✅ Clear all session data
$_SESSION = [];

// ✅ Regenerate session ID before destroying (prevents fixation attacks)
session_regenerate_id(true);

// ✅ Remove session cookie (if it exists)
if (session_id() !== '' || isset($_COOKIE[session_name()])) {
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(), 
            '', 
            time() - 42000, 
            $params['path'], 
            $params['domain'], 
            $params['secure'], 
            $params['httponly']
        );
    }
}

// ✅ Destroy session completely
session_destroy();

// ✅ Disable URL-based session IDs for extra safety
if (ini_get('session.use_trans_sid')) {
    ini_set('session.use_trans_sid', 0);
}

// ✅ Redirect admin back to login
header('Location: ../admin-login.php');
exit;
?>
