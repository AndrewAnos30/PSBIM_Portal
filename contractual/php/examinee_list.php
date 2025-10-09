<?php
// Include the database connection file
include('../connection/conn.php');

// Query to fetch all members
$sql = "SELECT id, username, firstname, middlename, lastname, seat_number FROM members";
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Fetch all records
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="search-container">
    <input type="text" id="searchInput" placeholder="Search members..." onkeyup="filterTable()">
</div>

<div class="table-responsive">
    <table class="attendance-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Seat Number</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($members): ?>
                <?php foreach ($members as $record): ?>
                    <?php
                        // Combine name parts safely
                        $fullName = htmlspecialchars(trim($record['lastname'] . ', ' . $record['firstname'] . ' ' . $record['middlename']));
                    ?>
                    <tr>
                        <td>
                            <a href="edit_member.php?id=<?php echo urlencode($record['id']); ?>" title="Edit Member">
                                <?php echo htmlspecialchars($record['id']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($record['username']); ?></td>
                        <td><?php echo $fullName ?: '-'; ?></td>
                        <td><?php echo htmlspecialchars($record['seat_number']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No member records found</td>
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
