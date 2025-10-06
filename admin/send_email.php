<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include '../connection/conn.php'; // defines $pdo

if (isset($_POST['send_email'])) {

    if (empty($_POST['examination_id'])) {
        die("Missing examination ID.");
    }

    $examId = $_POST['examination_id'];

    // ✅ Fetch only members whose examination_id matches and haven’t been sent emails yet
    $sql = "
        SELECT m.id, m.username, m.seat_number, m.firstname, m.middlename, 
               m.lastname, m.extensionname, m.email
        FROM members AS m
        WHERE m.examination_id = :exam_id
          AND m.email IS NOT NULL 
          AND m.email != ''
          AND NOT EXISTS (
              SELECT 1 FROM sent s
              WHERE s.member_email = m.email
                AND s.examination_id = m.examination_id
                AND s.status = 'sent'
          )
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['exam_id' => $examId]);
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($members as $member) {

        $recipientEmail = $member['email'];
        $fullName = $member['firstname'];
        if (!empty($member['middlename'])) $fullName .= " " . $member['middlename'];
        $fullName .= " " . $member['lastname'];
        if (!empty($member['extensionname'])) $fullName .= " " . $member['extensionname'];

        $username = $member['username'];
        $seatNumber = $member['seat_number'];

        try {
            // Configure PHPMailer
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp-pulse.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'pcp.mail123@gmail.com';
            $mail->Password   = '9sWBqELiCDCYnTH';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            // Sender and recipient
            $mail->setFrom('psbim@pcp.org.ph', 'Philippine College of Physicians');
            $mail->addAddress($recipientEmail, $fullName);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = "Your PCP Account Details";
            $mail->Body    = "
                Dear $fullName,<br><br>
                Here are your login details for the system:<br><br>
                <b>Username:</b> $username <br>
                <b>Password:</b> $seatNumber <br><br>
                Please keep this information secure.<br><br>
                Regards,<br>
                Philippine College of Physicians
            ";
            $mail->AltBody = "Dear $fullName,\n\nUsername: $username\nPassword: $seatNumber\n\nPlease keep this information secure.\n\nPhilippine College of Physicians";

            // ✅ Try sending the email
            $mail->send();
            $status = 'sent';

        } catch (Exception $e) {
            // ❌ Log failed sends too
            $status = 'failed';
        }

        // ✅ Log the result for this member (success or fail)
        $log = $pdo->prepare("
            INSERT INTO sent (examination_id, member_email, status, timestamp)
            VALUES (:exam_id, :email, :status, NOW())
        ");
        $log->execute([
            'exam_id' => $examId,
            'email' => $recipientEmail,
            'status' => $status
        ]);

        // Continue to next member
        continue;
    }

    // ✅ Redirect after completion
    header("Location: emails.php");
    exit;
}
?>
