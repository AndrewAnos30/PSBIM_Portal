<?php
// ✅ Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Restrict access to logged-in admins only
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php"); // Go two folders up to reach admin-login.php
    exit;
}

// Include the database connection
include('../connection/conn.php');

// Query to fetch all members
$sql = "SELECT username, CONCAT(firstname, ' ', lastname) AS full_name, room_number, seat_number, status FROM members";
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Fetch all members
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="table-responsive">
    <table class="members-table">
        <thead>
            <tr>
                <th>Username</th>
                <th>Full Name</th>
                <th>Room Number</th>
                <th>Seat</th>
                <th>Status</th>  <!-- Added Status column -->
            </tr>
        </thead>
        <tbody>
            <?php if ($members): ?>
                <?php foreach ($members as $member): ?>
                    <tr>
                        <td>
                            <!-- Make the username clickable and link to the edit form -->
                            <a href="edit_member.php?username=<?php echo urlencode($member['username']); ?>">
                                <?php echo htmlspecialchars($member['username']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($member['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($member['room_number']); ?></td>
                        <td><?php echo htmlspecialchars($member['seat_number']); ?></td>
                        <td><?php echo htmlspecialchars($member['status']); ?></td>  <!-- Display Status -->
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No members found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
