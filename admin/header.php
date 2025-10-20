<?php
// ✅ Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Restrict access to logged-in admins only
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php"); // Go two folders up to reach admin-login.php
    exit;
}
?>
<link rel="stylesheet" href="css/header.css">
<header class="admin-header">
    <div class="logo-container">
        <img src="img/pcp-logo.png" alt="">
    </div>
    <div class="admin-container">
        <span class="msg" id="welcome-msg"> Welcome, John Benedict Cueto |</span>
        <a href="logout.php" class="logout-link">Logout</a>
    </div>
</header>
