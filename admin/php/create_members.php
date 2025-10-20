<?php
session_start();

// ✅ Restrict access to logged-in admins only
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../admin-login.php"); // Go two folders up to reach admin-login.php
    exit;
}

// Include the database connection
include('../../connection/conn.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize form data
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $middlename = htmlspecialchars($_POST['middlename']);
    $extensionname = htmlspecialchars($_POST['extensionname']);
    $email = htmlspecialchars($_POST['email']);
    $examination_id = htmlspecialchars($_POST['examination_id']);
    $room_number = htmlspecialchars($_POST['room_number']);
    $seat_number = htmlspecialchars($_POST['seat_number']);
    $status = htmlspecialchars($_POST['status']); // Pass / Failed / Awaiting Exam

    // Validate required fields
    if (empty($username) || empty($password) || empty($firstname) || empty($lastname) || empty($email) || empty($examination_id) || empty($room_number) || empty($seat_number)) {
        die("All required fields must be filled out.");
    }

    // Hash the password before inserting into the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert new member
    $sql = "INSERT INTO members (
                username, password, firstname, lastname, middlename, extensionname,
                email, examination_id, room_number, seat_number, status
            ) 
            VALUES (
                :username, :password, :firstname, :lastname, :middlename, :extensionname,
                :email, :examination_id, :room_number, :seat_number, :status
            )";

    try {
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':middlename', $middlename);
        $stmt->bindParam(':extensionname', $extensionname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':examination_id', $examination_id);
        $stmt->bindParam(':room_number', $room_number);
        $stmt->bindParam(':seat_number', $seat_number);
        $stmt->bindParam(':status', $status);

        // Execute the query
        $stmt->execute();

        // Redirect after successful insert
        header('Location: ../members.php');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
