<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['contractual_id'])) {
        header("Location: ../contractual-login.php"); // redirect to contractual login page
        exit;
    }
?>
<link rel="stylesheet" href="css/navbar.css">
<link rel="stylesheet" href="css/passport.css">

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Load html2canvas for download -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<nav class="navbar">
    <ul>
        <li><a href="examinees.php" id="examinee-list">
            <i class="fas fa-users"></i> <span class="nav-text">EXAMINEE LIST</span>
        </a></li>
        <li><a href="am_attendance.php" id="am">
            <i class="fas fa-sun"></i> <span class="nav-text">AM</span>
        </a></li>
        <li><a href="pm_attendance.php" id="pm">
            <i class="fas fa-cloud-sun"></i> <span class="nav-text">PM</span>
        </a></li>
    </ul>
</nav>
