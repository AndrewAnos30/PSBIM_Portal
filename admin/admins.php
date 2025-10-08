<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/members.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'navbar.php'; ?>
    <div class="content-container">
        <div class="card-container">
            <div class="profile-header">
                <ul class="profile-tabs">
                    <li class="active">Admin</li>
                    <li>Create</li>
                </ul>
            </div>

            <!-- Admin Tab Content: Table of Admins -->
            <div class="lower-container">
                <div class="search-container">
                    <input type="text" id="searchInput" placeholder="Search admins..." onkeyup="filterTable()">
                </div>
                <?php include 'php/admin_list.php'; ?>
            </div>

            <!-- Create Tab Content: Form -->
            <div class="create-form-container" style="display: none;">
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

                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>

                    <button type="submit">Create Admin</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Add the script here -->
    <script>
        // Toggle between Admin list and Create form
        const tabs = document.querySelectorAll('.profile-tabs li');
        const createForm = document.querySelector('.create-form-container');
        const adminTable = document.querySelector('.lower-container');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                if (tab.textContent.trim() === 'Create') {
                    createForm.style.display = 'block';
                    adminTable.style.display = 'none';
                } else {
                    createForm.style.display = 'none';
                    adminTable.style.display = 'block';
                }
            });
        });

        // Simple search filter for Admin table
        function filterTable() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toLowerCase();
            const table = document.querySelector(".members-table tbody");
            const rows = table.getElementsByTagName("tr");

            for (let i = 0; i < rows.length; i++) {
                let text = rows[i].textContent.toLowerCase();
                rows[i].style.display = text.includes(filter) ? "" : "none";
            }
        }
    </script>
</body>
</html>
