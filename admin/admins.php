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
