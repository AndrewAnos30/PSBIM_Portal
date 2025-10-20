<?php
session_start();

// ✅ Restrict access to logged-in admins only
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../admin-login.php"); // Go two folders up to reach admin-login.php
    exit;
}
// Return JSON response for AJAX
header('Content-Type: application/json');

// Include your PDO connection file (adjusted path)
require_once __DIR__ . '/../../connection/conn.php';

// Initialize response structure
$response = [
    'success' => false,
    'message' => '',
    'inserted' => 0
];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method.');
    }

    if (
        !isset($_FILES['bulkUploadFile']) ||
        $_FILES['bulkUploadFile']['error'] !== UPLOAD_ERR_OK
    ) {
        throw new Exception('No CSV file uploaded or upload failed.');
    }

    $fileTmpPath = $_FILES['bulkUploadFile']['tmp_name'];

    if (!file_exists($fileTmpPath)) {
        throw new Exception('Temporary upload file not found.');
    }

    $handle = fopen($fileTmpPath, 'r');
    if ($handle === false) {
        throw new Exception('Unable to open uploaded CSV file.');
    }

    $row = 0;
    $inserted = 0;
    $errors = [];

    while (($data = fgetcsv($handle, 10000, ',')) !== false) {
        $row++;

        // Skip the header row
        if ($row === 1) continue;

        // Map columns
        $id             = trim($data[0] ?? '');
        $username       = trim($data[1] ?? '');
        $password       = trim($data[2] ?? '');
        $firstname      = trim($data[3] ?? '');
        $lastname       = trim($data[4] ?? '');
        $middlename     = trim($data[5] ?? '');
        $extensionname  = trim($data[6] ?? '');
        $email          = trim($data[7] ?? '');
        $examination_id = trim($data[8] ?? '');
        $room_number    = trim($data[9] ?? '');
        $seat_number    = trim($data[10] ?? '');
        $status         = trim($data[11] ?? 'Awaiting Exam');

        // Validate required fields
        if (
            $id === '' || $username === '' || $password === '' ||
            $firstname === '' || $lastname === '' || $email === '' ||
            $examination_id === ''
        ) {
            $errors[] = "Row $row skipped: missing required fields.";
            continue;
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("
                INSERT INTO members 
                (id, username, password, firstname, lastname, middlename, extensionname, email, examination_id, room_number, seat_number, status)
                VALUES (:id, :username, :password, :firstname, :lastname, :middlename, :extensionname, :email, :examination_id, :room_number, :seat_number, :status)
            ");

            $stmt->execute([
                ':id' => $id,
                ':username' => $username,
                ':password' => $hashed_password,
                ':firstname' => $firstname,
                ':lastname' => $lastname,
                ':middlename' => $middlename,
                ':extensionname' => $extensionname,
                ':email' => $email,
                ':examination_id' => $examination_id,
                ':room_number' => $room_number,
                ':seat_number' => $seat_number,
                ':status' => $status
            ]);

            $inserted++;
        } catch (PDOException $e) {
            $errors[] = "Row $row failed: " . $e->getMessage();
        }
    }

    fclose($handle);

    // Build response
    $response['inserted'] = $inserted;
    $response['success'] = empty($errors);
    $response['message'] = empty($errors)
        ? "All $inserted record(s) uploaded successfully!"
        : "Inserted $inserted record(s), but encountered " . count($errors) . " error(s):\n" .
          implode("\n", array_slice($errors, 0, 10)) .
          (count($errors) > 10 ? "\n...and more errors." : '');

} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = "❌ Upload failed: " . $e->getMessage();
}

// Return JSON
echo json_encode($response);
exit;
?>
