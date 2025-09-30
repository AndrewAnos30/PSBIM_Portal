<?php
// Include the database connection
include('../connection/conn.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize form data
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $middlename = htmlspecialchars($_POST['middlename']);
    $extensionname = htmlspecialchars($_POST['extensionname']);
    $gender = htmlspecialchars($_POST['gender']);
    $dob = htmlspecialchars($_POST['dob']);
    $email = htmlspecialchars($_POST['email']);
    $mobile = htmlspecialchars($_POST['mobile']);
    $examination_id = htmlspecialchars($_POST['examination_id']);
    $room_number = htmlspecialchars($_POST['room_number']);
    $seat_number = htmlspecialchars($_POST['seat_number']);
    $status = htmlspecialchars($_POST['status']); // Status field

    // Validate required fields
    if (empty($username) || empty($password) || empty($firstname) || empty($lastname) || empty($gender) || empty($dob) || empty($email) || empty($mobile) || empty($examination_id) || empty($room_number) || empty($seat_number)) {
        die("All fields are required.");
    }

    // Hash the password before inserting into the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert new member
    $sql = "INSERT INTO members (username, password, firstname, lastname, middlename, extensionname, gender, dob, email, mobile, examination_id, room_number, seat_number, status) 
            VALUES (:username, :password, :firstname, :lastname, :middlename, :extensionname, :gender, :dob, :email, :mobile, :examination_id, :room_number, :seat_number, :status)";

    try {
        // Prepare and execute the query
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password); // Bind the hashed password
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':middlename', $middlename);
        $stmt->bindParam(':extensionname', $extensionname);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':dob', $dob);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mobile', $mobile);
        $stmt->bindParam(':examination_id', $examination_id);
        $stmt->bindParam(':room_number', $room_number);
        $stmt->bindParam(':seat_number', $seat_number);
        $stmt->bindParam(':status', $status); // Bind the status field

        // Execute the query
        $stmt->execute();

        // Redirect to the members list page after successful insert
        header('Location: ../members.php');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
