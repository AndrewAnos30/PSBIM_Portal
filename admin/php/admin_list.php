<?php
// Include the database connection
include('../connection/conn.php');

// Query to fetch all admins
$sql = "SELECT username, email, role, status FROM admin";
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Fetch all admins
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="table-responsive">
    <table class="members-table">
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($admins): ?>
                <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td>  <a href="edit_admin.php?username=<?php echo urlencode($admin['username']); ?>" title="Edit Admin">
                                <?php echo htmlspecialchars($admin['username']); ?>
                            </a></td>
                        <td><?php echo htmlspecialchars($admin['email']); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst($admin['role'])); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst($admin['status'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No admins found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
