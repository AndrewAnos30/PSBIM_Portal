<?php
// passport_modal.php

// Prevent direct access — allow only via include
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('Location: ../index.php');
    exit;
}

// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Securely include DB connection
require_once '../connection/conn.php';

// Check if user is logged in
if (empty($_SESSION['user_id'])) {
    echo '<!-- Access denied: user not logged in -->';
    return;
}

try {
    // Fetch member details using session user_id for better security
    $stmt = $pdo->prepare("SELECT * FROM members WHERE id = :id LIMIT 1");
    $stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $member = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$member) {
        echo '<!-- Member not found -->';
        return;
    }

    // Prepare member data (safe with htmlspecialchars)
    $mid = htmlspecialchars($member['username']); 
    $firstname = htmlspecialchars($member['firstname']);
    $lastname = htmlspecialchars($member['lastname']);
    $middleinitial = !empty($member['middlename']) ? strtoupper(substr($member['middlename'], 0, 1)) . '.' : '';
    $extension = htmlspecialchars($member['extensionname']);
    $room = htmlspecialchars($member['room_number']);
    $seat = htmlspecialchars($member['seat_number']);
    $email = htmlspecialchars($member['email'] ?? '');

    // Prepare dynamic Google Form QR link (URL-encoded)
    $qr_link = "https://docs.google.com/forms/d/e/1FAIpQLSfH5_sFVdI2ccJBLkzany7IB2zCuJHNzJ1JjyKabvTP8KiLCA/viewform?usp=pp_url" .
        "&entry.1181033758=" . urlencode($member['username']) .
        "&entry.1249588435=" . urlencode($member['firstname']) .
        "&entry.543582119=" . urlencode($member['middlename']) .
        "&entry.434672025=" . urlencode($member['lastname']) .
        "&entry.349820305=" . urlencode($member['extensionname']) .
        "&entry.1111099222=" . urlencode($member['room_number']) .
        "&entry.1459707090=" . urlencode($member['seat_number']) .
        "&entry.1505939387=" . urlencode($member['email']);
} catch (PDOException $e) {
    error_log("Passport modal error: " . $e->getMessage());
    echo '<!-- Database error -->';
    return;
}
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
        <div class="passport-text mid"><?= $mid ?></div>
        <div class="passport-text firstname"><?= $firstname ?></div>
        <div class="passport-text lastname"><?= $lastname ?></div>
        <div class="passport-text middleinitial"><?= $middleinitial ?></div>
        <div class="passport-text extension"><?= $extension ?></div>
        <div class="passport-text room"><?= 'Room ' . $room ?></div>
        <div class="passport-text seat"><?= 'Seat ' . $seat ?></div>

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
    value: '<?= $qr_link ?>', // dynamic Google Form link
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

// Close when clicking outside modal
window.addEventListener("click", event => {
    if (event.target === modal) {
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
