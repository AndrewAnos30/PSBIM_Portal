<?php
session_start();
require_once '../connection/conn.php'; // Adjust path if needed

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

try {
    // Fetch logged-in member’s data
    $stmt = $pdo->prepare("SELECT * FROM members WHERE id = :id LIMIT 1");
    $stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $member = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$member) {
        $_SESSION['error'] = "Member data not found.";
        header('Location: ../index.php');
        exit;
    }
} catch (PDOException $e) {
    error_log("Dashboard error: " . $e->getMessage());
    $_SESSION['error'] = "Error fetching your data.";
    header('Location: ../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'navbar.php'; ?>
        <div class="content-container">
            <div class="card-container">
                <div class="profile-header">
                    <ul class="profile-tabs">
                        <li class="active">Profile</li>
                    </ul>
                </div>
                <div class="lower-container">
                    <div class="left-lower-container">
                        <div class="profile-image">
                            <img src="img/male.jpg" alt="Male">
                        </div>
                        <div class="exam-info">
                            <p><strong>Examination ID:</strong> <?= htmlspecialchars($member['examination_id']) ?></p>
                            <p><strong>Status:</strong> <?= htmlspecialchars($member['status']) ?></p>
                        </div>
                    </div>
                    <div class="right-lower-container">
                        <div class="profile-item" id="username">
                            <span><strong>Username:</strong> <?= htmlspecialchars($member['username']) ?></span>
                        </div>
                        <div class="profile-item" id="password">
                            <span><strong>Password:</strong> <?= htmlspecialchars($member['seat_number']) ?> </span>
                        </div>
                        <div class="profile-item" id="FirstName">
                            <span><strong>First Name:</strong> <?= htmlspecialchars($member['firstname']) ?></span>
                        </div>
                        <div class="profile-item" id="MiddleName">
                            <span><strong>Middle Name:</strong> <?= htmlspecialchars($member['middlename']) ?></span>
                        </div>
                        <div class="profile-item" id="LastName">
                            <span><strong>Last Name:</strong> <?= htmlspecialchars($member['lastname']) ?></span>
                        </div>
                        <div class="profile-item" id="ExtensionName">
                            <span><strong>Extension Name:</strong> <?= htmlspecialchars($member['extensionname']) ?></span>
                        </div>
                        <div class="profile-item" id="Email">
                            <span><strong>Email:</strong> <?= htmlspecialchars($member['email']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>
</html>
