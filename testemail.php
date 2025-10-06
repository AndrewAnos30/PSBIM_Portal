<?php
include 'connection/conn.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_email'])) {

    try {
        $mail = new PHPMailer(true);
        
        // SMTP Settings
        $mail->isSMTP();
        $mail->Host       = 'smtp-pulse.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'pcp.mail123@gmail.com';
        $mail->Password   = '9sWBqELiCDCYnTH';
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;
        
        // Sender & Recipient
        $mail->setFrom('psbim@pcp.org.ph', 'Philippine College of Physicians');
        $mail->addAddress('johnandrew.anos@pcp.org.ph', 'John Andrew Anos'); // Recipient email and name
        
        // Email Content
        $mail->isHTML(true);
        $mail->Subject = 'Test Email';
        $mail->Body    = 'Sent';
        
        // Send email
        $mail->send();

        $message = "✅ Email successfully sent to John Andrew Anos (johnandrew.anos@pcp.org.ph)!";
        
    } catch (Exception $e) {
        $message = "❌ Error sending email: {$mail->ErrorInfo}";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Sending</title>
</head>
<body>

    <!-- Display message after attempting to send email -->
    <p><?php echo $message; ?></p>

    <!-- Form to trigger the email sending -->
    <form action="" method="POST">
        <button type="submit" name="send_email">Send Test Email</button>
    </form>

</body>
</html>
