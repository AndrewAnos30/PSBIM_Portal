<?php
include('../connection/conn.php');
header('Content-Type: application/json');

if (!isset($_POST['username'])) {
    echo json_encode(['success' => false, 'message' => 'Username missing']);
    exit;
}

$username = trim($_POST['username']);

try {
    $sql = "
        SELECT 
            m.id,
            m.examination_id,
            m.username,
            CONCAT(m.lastname, ', ', m.firstname, ' ', m.middlename) AS full_name,
            m.training_institution,
            m.room_number,
            m.seat_number,
            m.prc_number,
            IFNULL(a.attendance, 0) AS am_attendance
        FROM members m
        LEFT JOIN am_attendance a ON m.username = a.username
        WHERE m.username = ?
        LIMIT 1
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$member) {
        echo json_encode(['success' => false, 'message' => 'Member not found']);
        exit;
    }

    echo json_encode(['success' => true, 'data' => $member]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
