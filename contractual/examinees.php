<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examinee List</title>
    <link rel="stylesheet" href="css/attendance.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'navbar.php'; ?>

    <div class="content-container">
        <div class="card-container">
            <div class="profile-header">
                <h2 style="color: #600; margin: 0;">Examinee List</h2>
            </div>

            <!-- Attendance Table -->
            <div class="lower-container">
                <?php include('php/examinee_list.php'); ?>
            </div>
        </div>
    </div>

    <!-- No tab toggle script needed anymore -->

</body>
</html>
