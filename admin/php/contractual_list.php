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

// ✅ Include the database connection
include('../connection/conn.php');

// ✅ Fetch contractual data
$sql = "SELECT username, examination_id, room_number FROM contractual";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$contractuals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="table-responsive">
    <table class="members-table">
        <thead>
            <tr>
                <th>Username</th>
                <th>Examination ID</th>
                <th>Room Number</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($contractuals): ?>
                <?php foreach ($contractuals as $contractual): ?>
                    <tr>
                        <td>
                            <a href="edit_contractual.php?username=<?php echo urlencode($contractual['username']); ?>" title="Edit Contractual">
                                <?php echo htmlspecialchars($contractual['username']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($contractual['examination_id']); ?></td>
                        <td><?php echo htmlspecialchars($contractual['room_number']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No contractual staff found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
