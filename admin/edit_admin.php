<?php


// Include the database connection
include('../connection/conn.php');

// Get the username from the URL
$username = isset($_GET['username']) ? $_GET['username'] : '';

// Query to fetch the admin's details based on the username
$sql = "SELECT * FROM admin WHERE username = :username";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->execute();

// Fetch the admin details
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// If admin exists, display the form, otherwise show an error
if (!$admin) {
    echo 'Admin not found!';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin - <?php echo htmlspecialchars($admin['username']); ?></title>
    <link rel="stylesheet" href="css/members.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'navbar.php'; ?>

    <div class="content-container">
        <div class="card-container">
            <div class="profile-header">
                <ul class="profile-tabs">
                    <li class="active">Edit Admin</li>
                </ul>
            </div>

            <!-- Edit Admin Form -->
            <div class="edit-form-container">
                <form action="php/update_admin.php" method="POST" class="create-form">

                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($admin['id']); ?>">

                    <!-- Username (Read-only) -->
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" readonly>

                    <!-- Email -->
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required>

                    <!-- Role -->
                    <label for="role">Role:</label>
                    <select id="role" name="role" required>
                        <option value="Admin" <?php echo $admin['role'] == 'Admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="Super Admin" <?php echo $admin['role'] == 'Super Admin' ? 'selected' : ''; ?>>Super Admin</option>
                    </select>

                    <!-- Status -->
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="Active" <?php echo $admin['status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                        <option value="Inactive" <?php echo $admin['status'] == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>

                    <!-- Password (Optional - Depending on your system, you can either allow password change or leave it unchanged) -->
                    <label for="password">Password (Leave blank if not changing):</label>
                    <input type="password" id="password" name="password" placeholder="Enter new password">

                    <!-- Created At (Read-only) -->
                    <label for="created_at">Created At:</label>
                    <input type="text" id="created_at" name="created_at" value="<?php echo htmlspecialchars($admin['created_at']); ?>" readonly>

                    <button type="submit">Update Admin</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Function to toggle the content between Edit and Admin tabs if you want
        const tabs = document.querySelectorAll('.profile-tabs li');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Toggle active class
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
            });
        });
    </script>
</body>
</html>
