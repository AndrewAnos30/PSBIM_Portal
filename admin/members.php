<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
    <link rel="stylesheet" href="css/members.css">
</head>
<body>
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

            <!-- Members Tab Content: Table of Members -->
            <div class="lower-container">
                <div class="search-container">
                <input type="text" id="searchInput" placeholder="Search members..." onkeyup="filterTable()">
            </div>
            <?php include('php/members_list.php'); ?>
            </div>

            <!-- Create Tab Content: Form -->
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

                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>

                    <label for="dob">Date of Birth:</label>
                    <input type="date" id="dob" name="dob" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="mobile">Mobile:</label>
                    <input type="text" id="mobile" name="mobile" required>

                    <label for="examination_id">Examination ID:</label>
                    <input type="text" id="examination_id" name="examination_id" required>

                    <!-- Additional Fields for Room and Seat Number -->
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
                    
                    <button type="submit">Create Member</button>
                </form>

                <!-- Bulk Upload Button -->
                <button id="bulkUploadButton" onclick="triggerFileUpload()">Bulk Upload examinee</button>

                <!-- Bulk Upload Form (hidden initially) -->
                <div id="bulkUploadForm" style="display: none;">
                    <h3>Upload CSV for Bulk Member Creation</h3>
                    <input type="file" id="bulkUploadFile" name="bulkUploadFile" accept=".csv" required style="display:none;">
                    <button type="submit" onclick="submitBulkUpload()">Upload CSV</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add the script here -->
    <script>
        // Function to toggle the content between Create and Members
        const tabs = document.querySelectorAll('.profile-tabs li');
        const createForm = document.querySelector('.create-form-container');
        const membersTable = document.querySelector('.lower-container');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove 'active' class from all tabs
                tabs.forEach(t => t.classList.remove('active'));
                
                // Add 'active' class to the clicked tab
                tab.classList.add('active');

                // Toggle content visibility based on the active tab
                if (tab.textContent.trim() === 'Create') {
                    createForm.style.display = 'block';
                    membersTable.style.display = 'none';
                } else {
                    createForm.style.display = 'none';
                    membersTable.style.display = 'block';
                }
            });
        });
    </script>
    <script>
        // Function to trigger the file upload dialog when "Bulk Upload" button is clicked
        function triggerFileUpload() {
            // Trigger the file input click event
            document.getElementById('bulkUploadFile').click();
        }

        // Optional: Submit the file upload when the user selects a file (you can customize this as needed)
        document.getElementById('bulkUploadFile').addEventListener('change', function() {
            if (this.files.length > 0) {
                // Automatically submit the form or do any other logic here
                alert('File selected: ' + this.files[0].name);  // You can replace this with actual upload logic
            }
        });

        // Optional: If you want to submit the bulk upload form when the button is clicked
        function submitBulkUpload() {
            // Here you would typically submit the form or trigger a file upload process
            alert('File uploaded!');
        }
    </script>
</body>
</html>
