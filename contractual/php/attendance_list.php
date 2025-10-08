<?php
// Include the database connection file
include('../connection/conn.php');

// Query to fetch all attendance records
$sql = "SELECT id, username, am, am_timestamp, pm, pm_timestamp FROM attendance";
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Fetch all records
$attendances = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="search-container">
    <input type="text" id="searchInput" placeholder="Search attendance..." onkeyup="filterTable()">
</div>
<div class="table-responsive">
    <table class="attendance-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>AM Timestamp</th>
                <th>AM</th>
                <th>PM Timestamp</th>
                <th>PM</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($attendances): ?>
                <?php foreach ($attendances as $record): ?>
                    <tr>
                        <td>
                            <a href="edit_attendance.php?id=<?php echo urlencode($record['id']); ?>" title="Edit Attendance">
                                <?php echo htmlspecialchars($record['id']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($record['username']); ?></td>
                        <td><?php echo $record['am_timestamp'] ? htmlspecialchars($record['am_timestamp']) : '-'; ?></td>
                        <td>
                            <?php if ($record['am']): ?>
                                <span style="color: green; font-weight: bold;">✔ Present</span>
                            <?php else: ?>
                                <span style="color: red;">✖ Absent</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $record['pm_timestamp'] ? htmlspecialchars($record['pm_timestamp']) : '-'; ?></td>
                        <td>
                            <?php if ($record['pm']): ?>
                                <span style="color: green; font-weight: bold;">✔ Present</span>
                            <?php else: ?>
                                <span style="color: red;">✖ Absent</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No attendance records found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function filterTable() {
    const input = document.getElementById("searchInput");
    const filter = input.value.toLowerCase();
    const table = document.querySelector(".attendance-table tbody");
    const rows = table.getElementsByTagName("tr");

    for (let i = 0; i < rows.length; i++) {
        let cells = rows[i].getElementsByTagName("td");
        let match = false;
        for (let j = 0; j < cells.length; j++) {
            if (cells[j].textContent.toLowerCase().indexOf(filter) > -1) {
                match = true;
                break;
            }
        }
        rows[i].style.display = match ? "" : "none";
    }
}
</script>
