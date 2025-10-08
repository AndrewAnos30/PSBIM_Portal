<?php
// Include the database connection
include('../connection/conn.php');

// Get the username from the URL
$username = isset($_GET['username']) ? $_GET['username'] : '';

// Query to fetch the member's details based on the username
$sql = "SELECT * FROM members WHERE username = :username";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->execute();

// Fetch the member details
$member = $stmt->fetch(PDO::FETCH_ASSOC);

// If member does not exist
if (!$member) {
    echo 'Member not found!';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Member - <?php echo htmlspecialchars($member['username']); ?></title>
    <link rel="stylesheet" href="css/members.css">
</head>
<body>
    <?php include('php/get_latest_examination_id.php'); ?>
    <?php include 'header.php'; ?>
    <?php include 'navbar.php'; ?>

    <div class="content-container">
        <div class="card-container">
            <div class="profile-header">
                <ul class="profile-tabs">
                    <li class="active">Edit Member</li>
                </ul>
            </div>

            <!-- Edit Member Form -->
            <div class="edit-form-container">
                <form action="php/update_member.php" method="POST" class="create-form">

                    <input type="hidden" name="username" value="<?php echo htmlspecialchars($member['username']); ?>">

                    <!-- First Name -->
                    <label for="firstname">First Name:</label>
                    <input type="text" id="firstname" name="firstname" 
                           value="<?php echo htmlspecialchars($member['firstname']); ?>" required>

                    <!-- Last Name -->
                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" 
                           value="<?php echo htmlspecialchars($member['lastname']); ?>" required>

                    <!-- Middle Name -->
                    <label for="middlename">Middle Name:</label>
                    <input type="text" id="middlename" name="middlename" 
                           value="<?php echo htmlspecialchars($member['middlename']); ?>">

                    <!-- Extension Name -->
                    <label for="extensionname">Extension Name:</label>
                    <input type="text" id="extensionname" name="extensionname" 
                           value="<?php echo htmlspecialchars($member['extensionname']); ?>">

                    <!-- Email -->
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo htmlspecialchars($member['email']); ?>" required>

                    <!-- Examination ID -->
                    <label for="examination_id">Examination ID:</label>
                    <input type="text" id="examination_id" name="examination_id" 
                           value="<?php echo htmlspecialchars($latestExaminationID); ?>" required>

                    <!-- Room Number -->
                    <label for="room_number">Room Number:</label>
                    <input type="text" id="room_number" name="room_number" 
                           value="<?php echo htmlspecialchars($member['room_number']); ?>" required>

                    <!-- Seat Number -->
                    <label for="seat_number">Seat Number:</label>
                    <input type="text" id="seat_number" name="seat_number" 
                           value="<?php echo htmlspecialchars($member['seat_number']); ?>" required>

                    <!-- 🔗 Google Form Link -->
                    <label for="link">Google Form Link (optional):</label>
                    <input type="url" id="link" name="link" 
                           value="<?php echo htmlspecialchars($member['link']); ?>" 
                           placeholder="https://forms.gle/...">

                    <!-- Status -->
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="Awaiting Exam" <?php echo $member['status'] == 'Awaiting Exam' ? 'selected' : ''; ?>>Awaiting Exam</option>
                        <option value="Passed" <?php echo $member['status'] == 'Passed' ? 'selected' : ''; ?>>Passed</option>
                        <option value="Failed" <?php echo $member['status'] == 'Failed' ? 'selected' : ''; ?>>Failed</option>
                    </select>

                    <!-- Password (optional) -->
                    <label for="password">Password (Leave blank if not changing):</label>
                    <input type="password" id="password" name="password" placeholder="Enter new password">

                    <button type="submit">Update Member</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
