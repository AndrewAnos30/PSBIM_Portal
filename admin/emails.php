<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Dashboard</title>
    <link rel="stylesheet" href="css/members.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'navbar.php'; ?>
    <div class="content-container">
        <div class="card-container">
            <div class="profile-header">
                <ul class="profile-tabs">
                    <!-- Changed the order of the tabs -->
                    <li class="active">Emails</li>
                    <li>Sent</li>
                    <li>Create Email</li>
                </ul>
            </div>

            <!-- Emails Tab Content: Table of Emails -->
            <div class="lower-container">
                <div class="search-container">
                    <input type="text" id="searchInput" placeholder="Search emails..." onkeyup="filterTable()">
                </div>
                <?php include 'php/email_list.php'; ?>
            </div>

            <!-- Sent Tab Content: Display Sent Emails -->
            <div class="sent-container" style="display: none;">
                <div class="search-container">
                    <input type="text" id="searchInputSent" placeholder="Search sent emails..." onkeyup="filterSentTable()">
                </div>
                <?php include 'php/sent_list.php'; ?>
            </div>

            <!-- Create Email Tab Content: Form to Create New Email -->
            <div class="create-form-container" style="display: none;">
                <form action="php/create_email.php" method="POST" class="create-form">
                    <!-- Email Subject -->
                    <label for="subject">Email Subject:</label>
                    <input type="text" id="subject" name="subject" required>

                    <!-- Examination ID -->
                    <label for="examination_id">Examination ID:</label>
                    <input type="text" id="examination_id" name="examination_id" required>

                    <!-- Submit Button -->
                    <button type="submit">Send Email</button>
                </form>
            </div>

        </div>
    </div>

    <!-- Add the script here -->
    <script>
        // Function to toggle the content between Emails, Sent, and Create Email tabs
        const tabs = document.querySelectorAll('.profile-tabs li');
        const emailTabContent = document.querySelector('.lower-container');
        const sentTabContent = document.querySelector('.sent-container');
        const createEmailForm = document.querySelector('.create-form-container');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove 'active' class from all tabs
                tabs.forEach(t => t.classList.remove('active'));
                
                // Add 'active' class to the clicked tab
                tab.classList.add('active');

                // Toggle content visibility based on the active tab
                if (tab.textContent.trim() === 'Emails') {
                    emailTabContent.style.display = 'block';
                    sentTabContent.style.display = 'none';
                    createEmailForm.style.display = 'none';
                } else if (tab.textContent.trim() === 'Sent') {
                    emailTabContent.style.display = 'none';
                    sentTabContent.style.display = 'block';
                    createEmailForm.style.display = 'none';
                } else if (tab.textContent.trim() === 'Create Email') {
                    emailTabContent.style.display = 'none';
                    sentTabContent.style.display = 'none';
                    createEmailForm.style.display = 'block';
                }
            });
        });

        // Search filter functionality for email table
        function filterTable() {
            let input = document.getElementById("searchInput");
            let filter = input.value.toUpperCase();
            let table = document.querySelector(".emails-table");
            let tr = table.getElementsByTagName("tr");

            for (let i = 0; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName("td");
                if (td) {
                    let subject = td[0].textContent || td[0].innerText;
                    let examId = td[1].textContent || td[1].innerText;
                    let memberId = td[2].textContent || td[2].innerText;

                    if (subject.toUpperCase().indexOf(filter) > -1 || 
                        examId.toUpperCase().indexOf(filter) > -1 || 
                        memberId.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        // Search filter functionality for sent email table
        function filterSentTable() {
            let input = document.getElementById("searchInputSent");
            let filter = input.value.toUpperCase();
            let table = document.querySelector(".sent-emails-table");
            let tr = table.getElementsByTagName("tr");

            for (let i = 0; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName("td");
                if (td) {
                    let subject = td[0].textContent || td[0].innerText;
                    let examId = td[1].textContent || td[1].innerText;
                    let memberId = td[2].textContent || td[2].innerText;

                    if (subject.toUpperCase().indexOf(filter) > -1 || 
                        examId.toUpperCase().indexOf(filter) > -1 || 
                        memberId.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
</body>
</html>
