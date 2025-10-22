<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['contractual_id'])) {
    header("Location: ../contractual-login.php"); // redirect to contractual login page
    exit;
}

// ✅ Determine displayed username
if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
    $contractual_username = htmlspecialchars($_SESSION['username']);
} else {
    // Use the contractual ID with leading zeros if needed
    $id = str_pad($_SESSION['contractual_id'], 2, '0', STR_PAD_LEFT);
    $contractual_username = "Contractual {$id}";
}
?>
<link rel="stylesheet" href="css/header.css">

<header class="admin-header">
    <div class="logo-container">
        <img src="img/pcp-logo.png" alt="PCP Logo">
    </div>
    <div class="admin-container">
        <span class="msg" id="welcome-msg">
            Welcome, <?= $contractual_username ?> |
        </span>
        <a href="logout.php" class="logout-link">Logout</a>
    </div>
</header>
