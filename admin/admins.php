<?php
session_start();

// ✅ Restrict access to logged-in admins only
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php"); // Go one folder back to admin-login
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/members.css">
</head>
<body>
    <?php include 'php/get_latest_examination_id.php'; ?>
    <?php include 'header.php'; ?>
    <?php include 'navbar.php'; ?>

    <div class="content-container">
        <div class="card-container">
            <div class="profile-header">
                <ul class="profile-tabs">
                    <li class="active" data-tab="admin">Admin</li>
                    <li data-tab="create-admin">Create Admin</li>
                    <li data-tab="create-contractual">Create Contractual</li>
                </ul>
            </div>

            <!-- Admin Tab Content -->
            <div class="lower-container" data-content="admin">
                <div class="search-container">
                    <input type="text" id="searchInput" placeholder="Search admins..." onkeyup="filterTable()">
                </div>
                <?php include 'php/admin_list.php'; ?>
            </div>

            <!-- Create Admin Form -->
            <div class="create-form-container" data-content="create-admin" style="display: none;">
                <form action="php/create_admin.php" method="POST" class="create-form">
                    
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="role">Role:</label>
                    <select id="role" name="role" required>
                        <option value="Super Admin">Super Admin</option>
                        <option value="Admin">Admin</option>
                        <option value="Contractual">Contractual</option>
                    </select>

                    <button type="submit">Create Admin</button>
                </form>
            </div>

            <!-- Create Contractual Form -->
            <div class="create-form-container" data-content="create-contractual" style="display: none;">
                <form action="php/create_contractual.php" method="POST" class="create-form">

                    <label for="username_contractual">Username:</label>
                    <input type="text" id="username_contractual" name="username" required>

                    <label for="password_contractual">Password:</label>
                    <input type="password" id="password_contractual" name="password" required>

                    <label for="role_contractual">Role:</label>
                    <select id="role_contractual" name="role" required>
                        <option value="Contractual">Contractual</option>
                    </select>

                    <label for="status_contractual">Status:</label>
                    <select id="status_contractual" name="status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>

                    <!-- New Fields -->
                    <label for="examination_id_contractual">Examination ID:</label>
                    <input type="text" id="examination_id_contractual" name="examination_id" value="<?php echo htmlspecialchars($latestExaminationID); ?>">

                    <label for="room_number_contractual">Room Number:</label>
                    <input type="text" id="room_number_contractual" name="room_number">

                    <button type="submit">Create Contractual</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        const tabs = document.querySelectorAll('.profile-tabs li');
        const contentSections = document.querySelectorAll('[data-content]');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Highlight active tab
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                // Show relevant content
                const selected = tab.dataset.tab;
                contentSections.forEach(section => {
                    section.style.display = section.dataset.content === selected ? 'block' : 'none';
                });
            });
        });

        // Simple search filter for Admin table
        function filterTable() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toLowerCase();
            const table = document.querySelector(".members-table tbody");
            const rows = table.getElementsByTagName("tr");

            for (let i = 0; i < rows.length; i++) {
                const text = rows[i].textContent.toLowerCase();
                rows[i].style.display = text.includes(filter) ? "" : "none";
            }
        }
    </script>
</body>
</html>
