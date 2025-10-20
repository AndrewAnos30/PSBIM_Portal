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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize form data
    $username = htmlspecialchars($_POST['username']);
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $middlename = htmlspecialchars($_POST['middlename']);
    $extensionname = htmlspecialchars($_POST['extensionname']);
    $email = htmlspecialchars($_POST['email']);
    $examination_id = htmlspecialchars($_POST['examination_id']);
    $room_number = htmlspecialchars($_POST['room_number']);
    $seat_number = htmlspecialchars($_POST['seat_number']);
    $status = htmlspecialchars($_POST['status']);
    $password = htmlspecialchars($_POST['password']);

    // Base SQL (no gender, dob, or mobile)
    $sql = "UPDATE members SET 
                firstname = :firstname, 
                lastname = :lastname, 
                middlename = :middlename, 
                extensionname = :extensionname, 
                email = :email, 
                examination_id = :examination_id, 
                room_number = :room_number, 
                seat_number = :seat_number, 
                status = :status";

    // If new password provided → include it in the update
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql .= ", password = :password";
    }

    $sql .= " WHERE username = :username";

    try {
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':middlename', $middlename);
        $stmt->bindParam(':extensionname', $extensionname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':examination_id', $examination_id);
        $stmt->bindParam(':room_number', $room_number);
        $stmt->bindParam(':seat_number', $seat_number);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':username', $username);

        if (!empty($password)) {
            $stmt->bindParam(':password', $hashedPassword);
        }

        // Execute the query
        $stmt->execute();

        // Redirect to members list after successful update
        header("Location: ../members.php");
        exit;

    } catch (PDOException $e) {
        echo "Error updating member: " . $e->getMessage();
    }
}
?>
