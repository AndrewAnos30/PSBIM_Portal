<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Restrict access to logged-in admins only
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../admin-login.php");
    exit;
}

include __DIR__ . '/../../connection/conn.php';

try {
    // ✅ Morning Attendance Query (includes training & prc)
    $morningQuery = "
        SELECT 
            m.username,
            CONCAT(m.firstname, ' ', m.middlename, ' ', m.lastname, ' ', m.extensionname) AS fullname,
            m.training_institution,
            m.room_number,
            m.seat_number,
            m.prc_number,
            a.attendance,
            a.timestamp
        FROM members m
        INNER JOIN am_attendance a ON m.username = a.username
        WHERE a.attendance IS NOT NULL
        ORDER BY a.timestamp DESC
    ";
    $morningStmt = $pdo->query($morningQuery);
    $morningData = $morningStmt->fetchAll(PDO::FETCH_ASSOC);

    // ✅ Afternoon Attendance Query (includes training & prc)
    $afternoonQuery = "
        SELECT 
            m.username,
            CONCAT(m.firstname, ' ', m.middlename, ' ', m.lastname, ' ', m.extensionname) AS fullname,
            m.training_institution,
            m.room_number,
            m.seat_number,
            m.prc_number,
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
                <th>Training Institution</th>
                <th>Room Number</th>
                <th>Seat Number</th>
                <th>PRC Number</th>
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
                        <td><?= htmlspecialchars($row['training_institution']); ?></td>
                        <td><?= htmlspecialchars($row['room_number']); ?></td>
                        <td><?= htmlspecialchars($row['seat_number']); ?></td>
                        <td><?= htmlspecialchars($row['prc_number']); ?></td>
                        <td><?= htmlspecialchars($row['attendance'] ?? '—'); ?></td>
                        <td><?= htmlspecialchars($row['timestamp'] ?? '—'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8" style="text-align:center;">No morning attendance records found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    function downloadCSV() {
        const table = document.getElementById("morningMembers");
        const rows = table.querySelectorAll("tbody tr");

        // ✅ Updated CSV header (removed Attendance and Timestamp)
        let csv = "Username,Full Name,Training Institution,Room Number,Seat Number,PRC Number\n";

        // ✅ Loop through table rows
        rows.forEach(row => {
            const cols = row.querySelectorAll("td");
            if (cols.length >= 8) {
                const username = cols[0].innerText.trim();
                const fullname = cols[1].innerText.trim();
                const training = cols[2].innerText.trim();
                const room = cols[3].innerText.trim();
                const seat = cols[4].innerText.trim();
                const prc = cols[5].innerText.trim();

                // ✅ Excluded attendance and timestamp from CSV output
                csv += `"${username}","${fullname}","${training}","${room}","${seat}","${prc}"\n`;
            }
        });

        // ✅ Create and trigger download
        const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
        const url = URL.createObjectURL(blob);
        const link = document.createElement("a");
        link.setAttribute("href", url);
        link.setAttribute("download", "morning_attendance.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // ✅ Search filter
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
