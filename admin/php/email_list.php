<?php
// Include the database connection file
include('../connection/conn.php');

// Query to fetch all emails
$sql = "SELECT id, subject, examination_id FROM emails";
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Fetch all emails
$emails = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="table-responsive">
    <table class="members-table">
        <thead>
            <tr>
                <th>Email ID</th>
                <th>Subject</th>
                <th>Examination ID</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($emails): ?>
                <?php foreach ($emails as $email): ?>
                    <tr>
                        <td>
                            <a href="view_email.php?id=<?php echo urlencode($email['id']); ?>" title="View Email">
                                <?php echo htmlspecialchars($email['id']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($email['subject']); ?></td>
                        <td><?php echo htmlspecialchars($email['examination_id']); ?></td>
                        <td>
                            <!-- Form to send email for each record, using an icon -->
                            <form action="send_email.php" method="POST" style="display: inline;">
                                <input type="hidden" name="email_id" value="<?php echo htmlspecialchars($email['id']); ?>">
                                <!-- ✅ Add this hidden field so send_email.php knows which exam to target -->
                                <input type="hidden" name="examination_id" value="<?php echo htmlspecialchars($email['examination_id']); ?>">
                                <button type="submit" name="send_email" class="btn-send-email" title="Send Email" style="background: none; border: none;">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No emails found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
