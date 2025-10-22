<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Restrict access to logged-in contractuals only
if (!isset($_SESSION['contractual_id'])) {
    header("Location: ../contractual-login.php");
    exit;
}

include('../connection/conn.php');

// =========================================================
// 🔍 Filter by contractual's assigned room (if any)
// =========================================================
$contractual_room = isset($_SESSION['room_number']) ? trim($_SESSION['room_number']) : null;

// Build query with optional WHERE clause
if (!empty($contractual_room)) {
    $sql = "SELECT id, username, firstname, middlename, lastname, seat_number, room_number 
            FROM members 
            WHERE room_number = :room_number
            ORDER BY seat_number ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['room_number' => $contractual_room]);
} else {
    // fallback: show all members if no room is assigned
    $sql = "SELECT id, username, firstname, middlename, lastname, seat_number, room_number 
            FROM members 
            ORDER BY room_number, seat_number ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- ========================== -->
<!-- 🔎 Search & Member Table -->
<!-- ========================== -->
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
                <th>Room Number</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($members): ?>
                <?php foreach ($members as $record): ?>
                    <?php
                        $fullName = htmlspecialchars(trim(
                            $record['lastname'] . ', ' . $record['firstname'] . ' ' . $record['middlename']
                        ));
                    ?>
                    <tr>
                        <td>
                            <a href="edit_member.php?id=<?= urlencode($record['id']); ?>" title="Edit Member">
                                <?= htmlspecialchars($record['id']); ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($record['username']); ?></td>
                        <td><?= $fullName ?: '-'; ?></td>
                        <td><?= htmlspecialchars($record['seat_number']); ?></td>
                        <td><?= htmlspecialchars($record['room_number']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No member records found</td>
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
            if (cells[j].textContent.toLowerCase().includes(filter)) {
                match = true;
                break;
            }
        }
        rows[i].style.display = match ? "" : "none";
    }
}
</script>
