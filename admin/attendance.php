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
    <title>Attendance</title>
    <link rel="stylesheet" href="css/members.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'navbar.php'; ?>
    <div class="content-container">
        <div class="card-container">
<div class="profile-header">
    <ul class="profile-tabs">
        <li class="active">Morning</li>
        <li>Afternoon</li>
    </ul>
</div>

<div class="lower-container">
    <?php include 'php/am_attendance_list.php'; ?>
    <?php include 'php/pm_attendance_list.php'; ?>
</div>

<script>
    const tabs = document.querySelectorAll('.profile-tabs li');
    const morningTable = document.querySelector('.morning-table');
    const afternoonTable = document.querySelector('.afternoon-table');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove 'active' class from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            // Show/hide tables
            if (tab.textContent.trim() === 'Morning') {
                morningTable.style.display = 'block';
                afternoonTable.style.display = 'none';
            } else {
                morningTable.style.display = 'none';
                afternoonTable.style.display = 'block';
            }
        });
    });

    // Search/filter function
    function filterTable(session) {
        let input, filter, table, tr, td, i, txtValue;
        if(session === 'morning') {
            input = document.getElementById('searchInputMorning');
            table = document.getElementById('morningMembers');
        } else {
            input = document.getElementById('searchInputAfternoon');
            table = document.getElementById('afternoonMembers');
        }

        filter = input.value.toUpperCase();
        tr = table.getElementsByTagName("tr");

        for (i = 1; i < tr.length; i++) {
            tr[i].style.display = "none";
            td = tr[i].getElementsByTagName("td");
            for (let j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                        break;
                    }
                }
            }
        }
    }
</script>
