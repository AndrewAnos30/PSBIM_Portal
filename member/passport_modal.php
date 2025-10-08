<?php
// passport_modal.php

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include DB connection
include('../connection/conn.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo '<!-- No user logged in -->';
    return;
}

// Fetch member details
$username = $_SESSION['username'];
$sql = "SELECT * FROM members WHERE username = :username LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->execute();
$member = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$member) {
    echo '<!-- Member not found -->';
    return;
}

// Prepare member data
$mid = htmlspecialchars($member['username']); 
$firstname = htmlspecialchars($member['firstname']);
$lastname = htmlspecialchars($member['lastname']);
$middleinitial = !empty($member['middlename']) ? strtoupper(substr($member['middlename'],0,1)).'.' : '';
$extension = htmlspecialchars($member['extensionname']);
$room = htmlspecialchars($member['room_number']);
$seat = htmlspecialchars($member['seat_number']);
$email = htmlspecialchars($member['email']); // Make sure this column exists

// Prepare dynamic Google Form QR link
$qr_link = "https://docs.google.com/forms/d/e/1FAIpQLSfH5_sFVdI2ccJBLkzany7IB2zCuJHNzJ1JjyKabvTP8KiLCA/viewform?usp=pp_url" .
    "&entry.1181033758=" . urlencode($member['username']) .
    "&entry.1249588435=" . urlencode($member['firstname']) .
    "&entry.543582119=" . urlencode($member['middlename']) .
    "&entry.434672025=" . urlencode($member['lastname']) .
    "&entry.349820305=" . urlencode($member['extensionname']) .
    "&entry.1111099222=" . urlencode($member['room_number']) .
    "&entry.1459707090=" . urlencode($member['seat_number']) .
    "&entry.1505939387=" . urlencode($member['email']);
?>

<link rel="stylesheet" href="css/passport.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script>

<!-- Passport Modal -->
<div id="passportModal" class="modal">
    <div class="modal-actions">
        <i id="downloadPassport" class="fas fa-download action-icon"></i>
        <i class="fas fa-times close action-icon"></i>
    </div>

    <div class="passport-preview" id="passportCard">
        <img src="img/passport.jpg" alt="ID Template" class="passport-bg">

        <!-- Overlay text fields -->
        <div class="passport-text mid"><?php echo $mid; ?></div>
        <div class="passport-text firstname"><?php echo $firstname; ?></div>
        <div class="passport-text lastname"><?php echo $lastname; ?></div>
        <div class="passport-text middleinitial"><?php echo $middleinitial; ?></div>
        <div class="passport-text extension"><?php echo $extension; ?></div>
        <div class="passport-text room"><?php echo 'Room '.$room; ?></div>
        <div class="passport-text seat"><?php echo 'Seat '.$seat; ?></div>

        <!-- QR Code -->
        <canvas id="qrCode" class="passport-text qr"></canvas>
    </div>
</div>

<script>
const modal = document.getElementById("passportModal");
const openBtn = document.getElementById("openPassport");
const closeBtn = document.querySelector(".modal .close");
const downloadBtn = document.getElementById("downloadPassport");
const passportCard = document.getElementById("passportCard");

// Generate QR code using QRious with dynamic member details
const qr = new QRious({
    element: document.getElementById('qrCode'),
    value: '<?php echo $qr_link; ?>', // dynamic Google Form link
    size: 150,           
    background: 'rgba(0,0,0,0)', 
    padding: 0           
});

// Open modal
openBtn?.addEventListener("click", e => {
    e.preventDefault();
    modal.style.display = "flex";
});

// Close modal
closeBtn?.addEventListener("click", () => {
    modal.style.display = "none";
});

// Close when clicking outside
window.addEventListener("click", event => {
    if(event.target === modal){
        modal.style.display = "none";
    }
});

// Download as image
downloadBtn?.addEventListener("click", () => {
    html2canvas(passportCard, { scale: 3 }).then(canvas => {
        const link = document.createElement("a");
        link.download = "passport.png";
        link.href = canvas.toDataURL("image/png");
        link.click();
    });
});
</script>
