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

// Include the database connection file
include('../connection/conn.php');

// Query to fetch all sent logs
$sql = "SELECT id, examination_id, member_email, status, timestamp FROM sent ORDER BY timestamp DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Fetch all sent logs
$sent_logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="table-responsive">
    <table class="members-table">
        <thead>
            <tr>
                <th>Sent ID</th>
                <th>Examination ID</th>
                <th>Member Email</th>
                <th>Status</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($sent_logs): ?>
                <?php foreach ($sent_logs as $log): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($log['id']); ?></td>
                        <td><?php echo htmlspecialchars($log['examination_id']); ?></td>
                        <td><?php echo htmlspecialchars($log['member_email']); ?></td>
                        <td>
                            <?php if ($log['status'] === 'sent'): ?>
                                <span style="color: green; font-weight: bold;">Sent</span>
                            <?php else: ?>
                                <span style="color: red; font-weight: bold;">Failed</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($log['timestamp']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No sent emails logged yet</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
