<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['contractual_id'])) {
        header("Location: ../contractual-login.php"); // redirect to contractual login page
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
