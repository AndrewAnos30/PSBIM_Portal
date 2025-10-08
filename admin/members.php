<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
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
                    <li class="active">Examinee</li>
                    <li>Create</li>
                </ul>
            </div>

            <!-- Members List -->
            <div class="lower-container">
                <div class="search-container">
                    <input type="text" id="searchInput" placeholder="Search members..." onkeyup="filterTable()">
                </div>
                <?php include('php/members_list.php'); ?>
            </div>

            <!-- Create Form -->
            <div class="create-form-container" style="display: none;">
                <form action="php/create_members.php" method="POST" class="create-form">
                    
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>

                    <label for="firstname">First Name:</label>
                    <input type="text" id="firstname" name="firstname" required>

                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" required>

                    <label for="middlename">Middle Name:</label>
                    <input type="text" id="middlename" name="middlename">

                    <label for="extensionname">Extension Name:</label>
                    <input type="text" id="extensionname" name="extensionname">

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="examination_id">Examination ID:</label>
                    <input type="text" id="examination_id" name="examination_id" 
                           value="<?php echo htmlspecialchars($latestExaminationID); ?>" required>

                    <label for="room_number">Room Number:</label>
                    <input type="text" id="room_number" name="room_number" required>

                    <label for="seat_number">Seat Number:</label>
                    <input type="text" id="seat_number" name="seat_number" required>

                    <!-- 🔗 Link Field (Google Form Prefilled URL) -->
                    <label for="link">Google Form Link (optional):</label>
                    <input type="url" id="link" name="link" placeholder="https://forms.gle/...">

                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="Awaiting Exam">Awaiting Exam</option>
                        <option value="Passed">Passed</option>
                        <option value="Failed">Failed</option>
                    </select>

                    <button type="submit">Create Member</button>
                </form>

                <!-- Bulk Upload Section -->
                <button id="bulkUploadButton" onclick="triggerFileUpload()">Bulk Upload Examinee</button>

                <div id="bulkUploadForm" style="display: none;">
                    <h3>Upload CSV for Bulk Member Creation</h3>
                    <input type="file" id="bulkUploadFile" name="bulkUploadFile" accept=".csv" required style="display:none;">
                    <button type="submit" onclick="submitBulkUpload()">Upload CSV</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab switching (Examinee ↔ Create)
        const tabs = document.querySelectorAll('.profile-tabs li');
        const createForm = document.querySelector('.create-form-container');
        const membersTable = document.querySelector('.lower-container');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                if (tab.textContent.trim() === 'Create') {
                    createForm.style.display = 'block';
                    membersTable.style.display = 'none';
                } else {
                    createForm.style.display = 'none';
                    membersTable.style.display = 'block';
                }
            });
        });

        // Bulk upload controls
        function triggerFileUpload() {
            document.getElementById('bulkUploadFile').click();
        }

        document.getElementById('bulkUploadFile').addEventListener('change', function() {
            if (this.files.length > 0) {
                alert('File selected: ' + this.files[0].name);
            }
        });

        function submitBulkUpload() {
            alert('File uploaded!');
        }
    </script>
</body>
</html>
