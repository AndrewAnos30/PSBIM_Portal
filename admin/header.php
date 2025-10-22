<?php
// ✅ Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Restrict access to logged-in admins only
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit;
}

// ✅ Get the logged-in username
$admin_username = $_SESSION['admin_username'] ?? 'Admin';
?>
<link rel="stylesheet" href="css/header.css">
<header class="admin-header">
    <div class="logo-container">
        <img src="img/pcp-logo.png" alt="Logo">
    </div>
    <div class="admin-container">
        <span class="msg" id="welcome-msg">
            Welcome, <?php echo htmlspecialchars($admin_username); ?> |
        </span>
        <a href="logout.php" class="logout-link">Logout</a>
    </div>
</header>
