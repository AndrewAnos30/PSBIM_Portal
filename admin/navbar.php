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
<link rel="stylesheet" href="css/navbar.css">
<link rel="stylesheet" href="css/passport.css">

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Load html2canvas for download -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<nav class="navbar">
    <ul>
        <li><a href="members.php" id="members">
            <i class="fas fa-users"></i> <span class="nav-text">MEMBERS</span>
        </a></li>
        <li><a href="emails.php">
            <i class="fas fa-envelope"></i> <span class="nav-text">EMAILING</span>
        </a></li>
        <li><a href="examinations.php">
            <i class="fas fa-file-alt"></i> <span class="nav-text">EXAMINATION</span>
        </a></li>
        <li><a href="reminders.php">
            <i class="fas fa-bell"></i> <span class="nav-text">REMINDERS</span>
        </a></li>
        <li><a href="admins.php">
            <i class="fas fa-user"></i> <span class="nav-text">ADMIN</span>
        </a></li>
        <li><a href="attendance.php">
            <i class="fas fa-user-check"></i> <span class="nav-text">ATTENDANCE</span>
        </a></li>
    </ul>
</nav>
