<?php
// Prevent direct access
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('Location: ../index.php');
    exit;
}

// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include DB connection
require_once '../connection/conn.php';

// Check if user is logged in
if (empty($_SESSION['user_id'])) {
    echo '<!-- Access denied: user not logged in -->';
    return;
}

try {
    // Fetch member details using session user_id
    $stmt = $pdo->prepare("SELECT * FROM members WHERE id = :id LIMIT 1");
    $stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $member = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$member) {
        echo '<!-- Member not found -->';
        return;
    }

    // Safe HTML variables
    $mid           = htmlspecialchars($member['username']);
    $firstname     = htmlspecialchars($member['firstname']);
    $lastname      = htmlspecialchars($member['lastname']);
    $middleinitial = !empty($member['middlename']) ? strtoupper(substr($member['middlename'],0,1)) . '.' : '';
    $extension     = htmlspecialchars($member['extensionname']);
    $room          = htmlspecialchars($member['room_number']);
    $seat          = htmlspecialchars($member['seat_number']);
    $email         = htmlspecialchars($member['email'] ?? '');
    $training      = htmlspecialchars($member['training_institution'] ?? '');
    $prc           = htmlspecialchars($member['prc_number'] ?? '');

    // Short QR link: pass only user_id
    $qr_link = "redirect_form.php?user_id=" . urlencode($member['id']);

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
        <div class="passport-text training"><?= $training ?></div>
        <div class="passport-text prc"><?= 'PRC: ' . $prc ?></div>

        <!-- QR Code -->
        <canvas id="qrCode" class="passport-text qr"></canvas>
    </div>
</div>

<script>
// Elements
const modal = document.getElementById("passportModal");
const openBtn = document.getElementById("openPassport");
const closeBtn = document.querySelector(".modal .close");
const downloadBtn = document.getElementById("downloadPassport");
const passportCard = document.getElementById("passportCard");

// ✅ Generate QR code with fixed size and low error correction
new QRious({
    element: document.getElementById('qrCode'),
    value: '<?= $qr_link ?>',
    size: 130,       // fixed size
    level: 'L',      // low error correction to keep QR small
    background: 'rgba(0,0,0,0)',
    padding: 0
});

// Modal behavior
openBtn?.addEventListener("click", e => {
    e.preventDefault();
    modal.style.display = "flex";
});
closeBtn?.addEventListener("click", () => modal.style.display = "none");
window.addEventListener("click", e => { if (e.target === modal) modal.style.display = "none"; });

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
