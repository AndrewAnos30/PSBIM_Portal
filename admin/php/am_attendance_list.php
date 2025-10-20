<?php
// ✅ Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Restrict access to logged-in admins only
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../admin-login.php"); // Go two folders up to reach admin-login.php
    exit;
}

include __DIR__ . '/../../connection/conn.php'; // connection outside admin folder

try {
    // ✅ Morning Attendance (show only if attendance record exists)
    $morningQuery = "
        SELECT 
            m.username,
            CONCAT(m.firstname, ' ', m.middlename, ' ', m.lastname, ' ', m.extensionname) AS fullname,
            m.room_number,
            m.seat_number,
            a.attendance,
            a.timestamp
        FROM members m
        INNER JOIN am_attendance a ON m.username = a.username
        WHERE a.attendance IS NOT NULL
        ORDER BY a.timestamp DESC
    ";
    $morningStmt = $pdo->query($morningQuery);
    $morningData = $morningStmt->fetchAll(PDO::FETCH_ASSOC);

    // ✅ Afternoon Attendance (show only if attendance record exists)
    $afternoonQuery = "
        SELECT 
            m.username,
            CONCAT(m.firstname, ' ', m.middlename, ' ', m.lastname, ' ', m.extensionname) AS fullname,
            m.room_number,
            m.seat_number,
            a.attendance,
            a.timestamp
        FROM members m
        INNER JOIN pm_attendance a ON m.username = a.username
        WHERE a.attendance IS NOT NULL
        ORDER BY a.timestamp DESC
    ";
    $afternoonStmt = $pdo->query($afternoonQuery);
    $afternoonData = $afternoonStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}
?>

<div class="table-responsive morning-table">
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search members..." onkeyup="filterTable()">
        <button id="downloadCSV" class="btn-download" onclick="downloadCSV()">
            Download CSV
        </button>
    </div>

    <table class="members-table" id="morningMembers">
        <thead>
            <tr>
                <th>Username</th>
                <th>Full Name</th>
                <th>Room Number</th>
                <th>Seat Number</th>
                <th>Attendance</th>
                <th>Time Stamp</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($morningData)): ?>
                <?php foreach ($morningData as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['username']); ?></td>
                        <td><?= htmlspecialchars($row['fullname']); ?></td>
                        <td><?= htmlspecialchars($row['room_number']); ?></td>
                        <td><?= htmlspecialchars($row['seat_number']); ?></td>
                        <td><?= htmlspecialchars($row['attendance'] ?? '—'); ?></td>
                        <td><?= htmlspecialchars($row['timestamp'] ?? '—'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center;">No morning attendance records found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function downloadCSV() {
    // Select the table
    const table = document.getElementById("morningMembers");
    const rows = table.querySelectorAll("tbody tr");

    // Prepare CSV header
    let csv = "Username,Full Name,Room Number,Seat Number\n";

    // Loop through table rows
    rows.forEach(row => {
        const cols = row.querySelectorAll("td");
        if (cols.length >= 4) {
            const username = cols[0].innerText.trim();
            const fullname = cols[1].innerText.trim();
            const room = cols[2].innerText.trim();
            const seat = cols[3].innerText.trim();
            csv += `"${username}","${fullname}","${room}","${seat}"\n`;
        }
    });

    // Create a downloadable CSV blob
    const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
    const url = URL.createObjectURL(blob);
    const link = document.createElement("a");
    link.setAttribute("href", url);
    link.setAttribute("download", "morning_attendance.csv");
    link.style.display = "none";
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Optional: Search Filter Function
function filterTable() {
    const input = document.getElementById("searchInput");
    const filter = input.value.toLowerCase();
    const rows = document.querySelectorAll("#morningMembers tbody tr");

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
}
</script>
