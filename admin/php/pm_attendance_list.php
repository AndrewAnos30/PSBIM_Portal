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

<!-- Afternoon Table -->
<div class="table-responsive afternoon-table" style="display:none;">
    <div class="search-container" style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
        <input type="text" id="pmSearchInput" placeholder="Search members..." onkeyup="filterPMTable()">
        <button id="downloadPMCSV" class="btn-download" onclick="downloadPMCSV()">Download CSV</button>
    </div>

    <table class="members-table" id="afternoonMembers">
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
            <?php if (!empty($afternoonData)): ?>
                <?php foreach ($afternoonData as $row): ?>
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
                <tr><td colspan="6" style="text-align:center;">No afternoon attendance records found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function downloadPMCSV() {
    const table = document.getElementById("afternoonMembers");
    const rows = Array.from(table.querySelectorAll("tbody tr"));

    // Extract only necessary columns
    const data = rows.map(row => {
        const cols = row.querySelectorAll("td");
        if (cols.length >= 4) {
            return {
                username: cols[0].innerText.trim(),
                fullname: cols[1].innerText.trim(),
                room_number: cols[2].innerText.trim(),
                seat_number: cols[3].innerText.trim()
            };
        }
        return null;
    }).filter(item => item !== null);

    // Sort by room_number descending
    data.sort((a, b) => {
        const roomA = parseInt(a.room_number) || 0;
        const roomB = parseInt(b.room_number) || 0;
        return roomB - roomA;
    });

    // Build CSV content
    let csv = "Username,Full Name,Room Number,Seat Number\n";
    data.forEach(row => {
        csv += `"${row.username}","${row.fullname}","${row.room_number}","${row.seat_number}"\n`;
    });

    // Trigger download
    const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
    const url = URL.createObjectURL(blob);
    const link = document.createElement("a");
    link.href = url;
    link.download = "afternoon_attendance.csv";
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function filterPMTable() {
    const input = document.getElementById("pmSearchInput");
    const filter = input.value.toLowerCase();
    const rows = document.querySelectorAll("#afternoonMembers tbody tr");

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
}
</script>
