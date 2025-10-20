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
    <title>Examination Dashboard</title>
    <link rel="stylesheet" href="css/examinations.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'navbar.php'; ?>
    <div class="content-container">
        <div class="card-container">
            <div class="profile-header">
                <ul class="profile-tabs">
                    <li class="active">Examinations</li>
                    <li>Create</li>
                </ul>
            </div>

            <!-- Examinations Tab Content: Table of Examinations -->
            <div class="lower-container">
                <div class="search-container">
                    <input type="text" id="searchInput" placeholder="Search examinations..." onkeyup="filterTable()">
                </div>
                <?php include('php/examinations_list.php'); ?>
            </div>

            <!-- Create Tab Content: Form -->
            <div class="create-form-container" style="display: none;">
                <form action="php/create_examination.php" method="POST" class="create-form">
                    
                    <label for="examination_id">Examination ID:</label>
                    <input type="text" id="examination_id" name="examination_id" required>

                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" required>

                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" required>

                    <label for="time">Time:</label>
                    <input type="time" id="time" name="time" required>

                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" required>

                    <button type="submit">Create Examination</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Add the script here -->
    <script>
        // Function to toggle the content between Create and Examinations
        const tabs = document.querySelectorAll('.profile-tabs li');
        const createForm = document.querySelector('.create-form-container');
        const examinationsTable = document.querySelector('.lower-container');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove 'active' class from all tabs
                tabs.forEach(t => t.classList.remove('active'));
                
                // Add 'active' class to the clicked tab
                tab.classList.add('active');

                // Toggle content visibility based on the active tab
                if (tab.textContent.trim() === 'Create') {
                    createForm.style.display = 'block';
                    examinationsTable.style.display = 'none';
                } else {
                    createForm.style.display = 'none';
                    examinationsTable.style.display = 'block';
                }
            });
        });
    </script>

</body>
</html>
