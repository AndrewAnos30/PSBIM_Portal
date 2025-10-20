<?php
// Start session only if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Prevent direct access to this file
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('Location: ../index.php');
    exit;
}

require_once '../connection/conn.php'; // adjust if your path differs

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

try {
    // Fetch member name details
    $stmt = $pdo->prepare("SELECT firstname, middlename, lastname, extensionname FROM members WHERE id = :id LIMIT 1");
    $stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Header fetch error: " . $e->getMessage());
    $member = ['firstname' => '', 'middlename' => '', 'lastname' => '', 'extensionname' => ''];
}

// Format middle name as first letter + "."
$middleInitial = '';
if (!empty($member['middlename'])) {
    $middleInitial = strtoupper(substr(trim($member['middlename']), 0, 1)) . '.';
}

// Build full name safely
$fullname = trim(
    htmlspecialchars($member['firstname'] ?? '') . 
    (!empty($middleInitial) ? ' ' . $middleInitial : '') . 
    ' ' . htmlspecialchars($member['lastname'] ?? '') . 
    (!empty($member['extensionname']) ? ' ' . htmlspecialchars($member['extensionname']) : '')
);
?>
<link rel="stylesheet" href="css/header.css">
<header class="member-header">
    <div class="logo-container">
        <img src="img/pcp-logo.png" alt="PCP Logo">
    </div>
    <div class="member-container">
        <span class="msg" id="welcome-msg">
            Welcome, Dr. <?= $fullname ?> |
        </span>
        <a href="../logout.php" class="logout-link" onclick="return confirm('Are you sure you want to log out?')">Logout</a>
    </div>
</header>
    