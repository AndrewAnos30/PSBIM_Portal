<?php
session_start();

// ✅ Restrict access to logged-in admins only
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../admin-login.php");
    exit;
}

// ✅ Include the database connection
include('../../connection/conn.php');

// ✅ Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $id              = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $examination_id  = trim($_POST['examination_id'] ?? '');
    $room_number     = trim($_POST['room_number'] ?? '');
    $status          = trim($_POST['status'] ?? '');

    // Basic validation
    if ($id <= 0) {
        die("Invalid contractual ID.");
    }
    if (empty($examination_id) || empty($room_number)) {
        die("Examination ID and room number are required.");
    }

    try {
        // ✅ Prepare SQL query dynamically
        if (!empty($status)) {
            $sql = "UPDATE contractual 
                    SET examination_id = :examination_id, 
                        room_number = :room_number, 
                        status = :status 
                    WHERE id = :id";
        } else {
            $sql = "UPDATE contractual 
                    SET examination_id = :examination_id, 
                        room_number = :room_number 
                    WHERE id = :id";
        }

        $stmt = $pdo->prepare($sql);

        // ✅ Bind parameters
        $stmt->bindParam(':examination_id', $examination_id, PDO::PARAM_STR);
        $stmt->bindParam(':room_number', $room_number, PDO::PARAM_STR);
        if (!empty($status)) {
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        }
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // ✅ Execute query
        $stmt->execute();

        // ✅ Redirect back to list page
        header("Location: ../contractual.php");
        exit;

    } catch (PDOException $e) {
        echo "Error updating contractual record: " . $e->getMessage();
    }
}
?>
