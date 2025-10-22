<?php
// ✅ Start session safely
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
    // ✅ Afternoon Attendance Query (includes training institution & prc)
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
                <th>Training Institution</th>
                <th>Room Number</th>
                <th>Seat Number</th>
                <th>PRC Number</th>
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
                        <td><?= htmlspecialchars($row['training_institution']); ?></td>
                        <td><?= htmlspecialchars($row['room_number']); ?></td>
                        <td><?= htmlspecialchars($row['seat_number']); ?></td>
                        <td><?= htmlspecialchars($row['prc_number']); ?></td>
                        <td><?= htmlspecialchars($row['attendance'] ?? '—'); ?></td>
                        <td><?= htmlspecialchars($row['timestamp'] ?? '—'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8" style="text-align:center;">No afternoon attendance records found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function csvEscape(value) {
    return `"${String(value).replace(/"/g, '""')}"`;
}

function downloadPMCSV() {
    const table = document.getElementById("afternoonMembers");
    const rows = Array.from(table.querySelectorAll("tbody tr"));

    // Extract required columns (excluding attendance and timestamp)
    const data = rows.map(row => {
        const cols = row.querySelectorAll("td");
        if (cols.length >= 8) {
            return {
                username: cols[0].innerText.trim(),
                fullname: cols[1].innerText.trim(),
                training: cols[2].innerText.trim(),
                room_number: cols[3].innerText.trim(),
                seat_number: cols[4].innerText.trim(),
                prc_number: cols[5].innerText.trim()
            };
        }
        return null;
    }).filter(item => item !== null);

    // Sort by room_number descending (handles both numbers & letters)
    data.sort((a, b) => b.room_number.localeCompare(a.room_number, undefined, { numeric: true, sensitivity: 'base' }));

    // Build CSV content
    let csv = "Username,Full Name,Training Institution,Room Number,Seat Number,PRC Number\n";
    data.forEach(row => {
        csv += [
            csvEscape(row.username),
            csvEscape(row.fullname),
            csvEscape(row.training),
            csvEscape(row.room_number),
            csvEscape(row.seat_number),
            csvEscape(row.prc_number)
        ].join(",") + "\n";
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
