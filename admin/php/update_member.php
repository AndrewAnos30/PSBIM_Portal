<?php
// Include the database connection
include('../../connection/conn.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $username = $_POST['username'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $middlename = $_POST['middlename'];
    $extensionname = $_POST['extensionname'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $examination_id = $_POST['examination_id'];
    $room_number = $_POST['room_number'];
    $seat_number = $_POST['seat_number'];
    $status = $_POST['status'];
    $password = $_POST['password'];

    // Initialize the SQL query for updating member details
    $sql = "UPDATE members SET 
                firstname = :firstname, 
                lastname = :lastname, 
                middlename = :middlename, 
                extensionname = :extensionname, 
                gender = :gender, 
                dob = :dob, 
                email = :email, 
                mobile = :mobile, 
                examination_id = :examination_id, 
                room_number = :room_number, 
                seat_number = :seat_number, 
                status = :status";

    // If a new password is provided, hash it and include it in the update query
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);  // Hash the password
        $sql .= ", password = :password";  // Add password to the query
    }

    $sql .= " WHERE username = :username";  // Use the username to find the member

    // Prepare and execute the statement
    $stmt = $pdo->prepare($sql);

    // Bind all the form values to the SQL statement
    $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
    $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
    $stmt->bindParam(':middlename', $middlename, PDO::PARAM_STR);
    $stmt->bindParam(':extensionname', $extensionname, PDO::PARAM_STR);
    $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
    $stmt->bindParam(':dob', $dob, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $stmt->bindParam(':examination_id', $examination_id, PDO::PARAM_STR);
    $stmt->bindParam(':room_number', $room_number, PDO::PARAM_STR);
    $stmt->bindParam(':seat_number', $seat_number, PDO::PARAM_STR);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);

    // If password was provided, bind the hashed password
    if (!empty($password)) {
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
    }

    // Execute the query
    try {
        $stmt->execute();
        // Redirect to members list or show success message
        header("Location: ../members.php");  // Redirect to the members list page
        exit;
    } catch (PDOException $e) {
        echo "Error updating member: " . $e->getMessage();
    }
}
?>
