<link rel="stylesheet" href="css/navbar.css">
<link rel="stylesheet" href="css/passport.css">

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Load html2canvas for download -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<nav class="navbar">
    <ul>
        <li><a href="member_dashboard.php" class="active">
            <i class="fas fa-user"></i> <span class="nav-text">PROFILE</span>
        </a></li>
        <li><a href="#" id="openPassport">
            <i class="fas fa-id-card"></i> <span class="nav-text">PASSPORT</span>
        </a></li>
        <li><a href="reminders.php">
            <i class="fas fa-bell"></i> <span class="nav-text">REMINDERS</span>
        </a></li>
    </ul>
</nav>

<?php include('passport_modal.php'); ?>
