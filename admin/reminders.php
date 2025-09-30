<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminder Dashboard</title>
    <link rel="stylesheet" href="css/reminders.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'navbar.php'; ?>
    <div class="content-container">
        <div class="card-container">
            <div class="profile-header">
                <ul class="profile-tabs">
                    <li class="active">Reminders</li>
                    <li>Create</li>
                </ul>
            </div>

            <!-- Reminders Tab Content: Table of Reminders -->
            <div class="lower-container">
                <div class="search-container">
                    <input type="text" id="searchInput" placeholder="Search reminders..." onkeyup="filterTable()">
                </div>
                <div class="table-responsive">
                    <table class="reminders-table">
                        <thead>
                            <tr>
                                <th>Reminder ID</th>
                                <th>Title</th>
                                <th>Link</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>RM001</td>
                                <td>Submit Examination Papers</td>
                                <td><a href="link_to_reminder_1" target="_blank">View</a></td>
                            </tr>
                            <tr>
                                <td>RM002</td>
                                <td>Check Examination Results</td>
                                <td><a href="link_to_reminder_2" target="_blank">View</a></td>
                            </tr>
                            <!-- Add more rows dynamically as needed -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Create Tab Content: Form -->
            <div class="create-form-container" style="display: none;">
                <form action="submit_reminder_form.php" method="POST" class="create-form">
                    
                    <label for="reminder_id">Reminder ID:</label>
                    <input type="text" id="reminder_id" name="reminder_id" required>

                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" required>

                    <label for="link">Link:</label>
                    <input type="url" id="link" name="link" required>

                    <button type="submit">Create Reminder</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Add the script here -->
    <script>
        // Function to toggle the content between Create and Reminders
        const tabs = document.querySelectorAll('.profile-tabs li');
        const createForm = document.querySelector('.create-form-container');
        const remindersTable = document.querySelector('.lower-container');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove 'active' class from all tabs
                tabs.forEach(t => t.classList.remove('active'));
                
                // Add 'active' class to the clicked tab
                tab.classList.add('active');

                // Toggle content visibility based on the active tab
                if (tab.textContent.trim() === 'Create') {
                    createForm.style.display = 'block';
                    remindersTable.style.display = 'none';
                } else {
                    createForm.style.display = 'none';
                    remindersTable.style.display = 'block';
                }
            });
        });
    </script>

</body>
</html>
