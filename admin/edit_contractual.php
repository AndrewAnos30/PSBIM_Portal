<?php
// ✅ Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Restrict access to logged-in admins only
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../admin-login.php");
    exit;
}

// ✅ Include database connection
include('../connection/conn.php');

// ✅ Get the username from the URL
$username = isset($_GET['username']) ? $_GET['username'] : '';

// ✅ Fetch contractual details
$sql = "SELECT * FROM contractual WHERE username = :username";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->execute();
$contractual = $stmt->fetch(PDO::FETCH_ASSOC);

// ✅ Handle missing record
if (!$contractual) {
    echo 'Contractual staff not found!';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Contractual - <?php echo htmlspecialchars($contractual['username']); ?></title>
    <link rel="stylesheet" href="css/members.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'navbar.php'; ?>

    <div class="content-container">
        <div class="card-container">
            <div class="profile-header">
                <ul class="profile-tabs">
                    <li class="active">Edit Contractual</li>
                </ul>
            </div>

            <!-- Edit Contractual Form -->
            <div class="edit-form-container">
                <form action="php/update_contractual.php" method="POST" class="create-form">

                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($contractual['id']); ?>">

                    <!-- Username (Read-only) -->
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($contractual['username']); ?>" readonly>

                    <!-- Examination ID -->
                    <label for="examination_id">Examination ID:</label>
                    <input type="text" id="examination_id" name="examination_id" value="<?php echo htmlspecialchars($contractual['examination_id']); ?>" required>

                    <!-- Room Number -->
                    <label for="room_number">Room Number:</label>
                    <input type="text" id="room_number" name="room_number" value="<?php echo htmlspecialchars($contractual['room_number']); ?>" required>

                    <!-- Status (optional if your table has it) -->
                    <?php if (isset($contractual['status'])): ?>
                        <label for="status">Status:</label>
                        <select id="status" name="status" required>
                            <option value="Active" <?php echo $contractual['status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                            <option value="Inactive" <?php echo $contractual['status'] == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    <?php endif; ?>

                    <!-- Created At (Read-only if exists) -->
                    <?php if (isset($contractual['created_at'])): ?>
                        <label for="created_at">Created At:</label>
                        <input type="text" id="created_at" name="created_at" value="<?php echo htmlspecialchars($contractual['created_at']); ?>" readonly>
                    <?php endif; ?>

                    <button type="submit">Update Contractual</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Optional tab toggle logic
        const tabs = document.querySelectorAll('.profile-tabs li');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
            });
        });
    </script>
</body>
</html>
