<?php
session_start();

// ✅ Restrict access to logged-in admins only
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../admin-login.php"); // Go two folders up to reach admin-login.php
    exit;
}

// Include the database connection file
include('../connection/conn.php');

// Query to fetch all examinations
$sql = "SELECT id, title, date, time, location FROM examinations";
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Fetch all examinations
$examinations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="table-responsive">
    <table class="examinations-table">
        <thead>
            <tr>
                <th>Examination ID</th>
                <th>Title</th>
                <th>Date</th>
                <th>Time</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($examinations): ?>
                <?php foreach ($examinations as $exam): ?>
                    <tr>
                        <td>
                            <!-- Only the Exam ID is clickable -->
                            <a href="edit_exam.php?id=<?php echo urlencode($exam['id']); ?>" title="Edit Examination">
                                <?php echo htmlspecialchars($exam['id']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($exam['title']); ?></td>
                        <td><?php echo htmlspecialchars($exam['date']); ?></td>
                        <td><?php echo htmlspecialchars($exam['time']); ?></td>
                        <td><?php echo htmlspecialchars($exam['location']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No examinations found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
