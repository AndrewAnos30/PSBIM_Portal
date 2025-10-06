<?php
include('../connection/conn.php');

// Get the exam ID from URL
$exam_code = isset($_GET['id']) ? $_GET['id'] : '';

if (!$exam_code) {
    echo 'Invalid Examination ID.';
    exit;
}

// Fetch exam details using id (varchar primary key)
$sql = "SELECT * FROM examinations WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $exam_code]);
$exam = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$exam) {
    echo 'Examination not found!';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Examination - <?php echo htmlspecialchars($exam['id']); ?></title>
    <link rel="stylesheet" href="css/members.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'navbar.php'; ?>

    <div class="content-container">
        <div class="card-container">
            <div class="profile-header">
                <ul class="profile-tabs">
                    <li class="active">Edit Examination</li>
                </ul>
            </div>

            <div class="edit-form-container">
                <form action="php/update_exam.php" method="POST" class="create-form">
                    <!-- ID (read-only) -->
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($exam['id']); ?>">

                    <label for="id_display">Examination ID:</label>
                    <input type="text" id="id_display" value="<?php echo htmlspecialchars($exam['id']); ?>" readonly>

                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($exam['title']); ?>" required>

                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($exam['date']); ?>" required>

                    <label for="time">Time:</label>
                    <input type="time" id="time" name="time" value="<?php echo htmlspecialchars($exam['time']); ?>" required>

                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($exam['location']); ?>" required>

                    <button type="submit">Update Examination</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
