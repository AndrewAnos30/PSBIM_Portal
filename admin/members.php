<?php
session_start();

// ✅ Restrict access to logged-in admins only
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit;
}
?>
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

            <!-- Examinee List -->
            <div class="lower-container">
                <div class="search-container">
                    <input type="text" id="searchInput" placeholder="Search examinees..." onkeyup="filterTable()">
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

                    <label for="training_institution">Training Institution:</label>
                    <input type="text" id="training_institution" name="training_institution" required>

                    <label for="prc_number">PRC Number:</label>
                    <input type="text" id="prc_number" name="prc_number" required>

                    <label for="examination_id">Examination ID:</label>
                    <input type="text" id="examination_id" name="examination_id"
                        value="<?php echo htmlspecialchars($latestExaminationID); ?>" required>

                    <label for="room_number">Room Number:</label>
                    <input type="text" id="room_number" name="room_number" required>

                    <label for="seat_number">Seat Number:</label>
                    <input type="text" id="seat_number" name="seat_number" required>

                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="Awaiting Exam">Awaiting Exam</option>
                        <option value="Passed">Passed</option>
                        <option value="Failed">Failed</option>
                    </select>

                    <button type="submit">Add Examinee</button>
                </form>

                <!-- Bulk Upload Section -->
                <div class="bulk-upload-section">
                    <button id="bulkUploadButton" class="btn-upload">BULK UPLOAD</button>

                    <div id="bulkUploadForm" style="display: none; margin-top: 1em;">
                        <h3>Upload Examinee List</h3>
                        <p>Upload a <strong>.csv file</strong> containing your examinees’ details.  
                        You can <a href="../csv/members_sample.csv" download style="color:#511b11; text-decoration:underline;">download a sample file here</a>.</p>

                        <form id="bulkUploadFormElement" enctype="multipart/form-data">
                            <input type="file" id="bulkUploadFile" name="bulkUploadFile" accept=".csv" required>
                            <button type="submit" class="btn-submit">Upload File</button>
                        </form>
                    </div>

                    <!-- Inline feedback message -->
                    <div id="uploadResult" class="alert" style="display:none;"></div>
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

        // Bulk Upload Logic
        const bulkButton = document.getElementById('bulkUploadButton');
        const bulkForm = document.getElementById('bulkUploadForm');
        const uploadResult = document.getElementById('uploadResult');

        bulkButton.addEventListener('click', () => {
            bulkForm.style.display = 'block';
            bulkButton.style.display = 'none';
        });

        document.getElementById('bulkUploadFormElement').addEventListener('submit', async (e) => {
            e.preventDefault();

            const file = document.getElementById('bulkUploadFile').files[0];
            if (!file) {
                showResult('⚠️ Please select a file first.', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('bulkUploadFile', file);

            showResult('⏳ Uploading, please wait...', 'warning');

            try {
                const response = await fetch('php/bulk_upload_members.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    showResult(`✅ Upload complete! ${result.inserted} record(s) added.`, 'success');
                } else if (result.inserted > 0) {
                    showResult(`⚠️ Some records were added, but others need fixing.`, 'warning');
                } else {
                    showResult(`❌ Upload failed. Please check your file and try again.`, 'error');
                }

            } catch (err) {
                showResult(`❌ Something went wrong. Please try again later.`, 'error');
            }
        });

        function showResult(message, type) {
            uploadResult.style.display = 'block';
            uploadResult.className = 'alert';
            if (type === 'success') uploadResult.classList.add('alert-success');
            else if (type === 'warning') uploadResult.classList.add('alert-warning');
            else uploadResult.classList.add('alert-error');
            uploadResult.textContent = message;
        }
    </script>
</body>
</html>
