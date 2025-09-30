<link rel="stylesheet" href="css/navbar.css">
<link rel="stylesheet" href="css/passport.css">

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Load html2canvas for download -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<nav class="navbar">
    <ul>
        <li><a href="member_dashboard.php" class="active">
            <i class="fas fa-user"></i> <span class="nav-text">PROFILE</span>
        </a></li>
        <li><a href="#" id="openPassport">
            <i class="fas fa-id-card"></i> <span class="nav-text">PASSPORT</span>
        </a></li>
        <li><a href="reminders.php">
            <i class="fas fa-bell"></i> <span class="nav-text">REMINDERS</span>
        </a></li>
    </ul>
</nav>


<!-- Passport Modal -->
<div id="passportModal" class="modal">
    <div class="modal-actions">
        <i id="downloadPassport" class="fas fa-download action-icon"></i>
        <i class="fas fa-times close action-icon"></i>
    </div>

    <div class="passport-preview" id="passportCard">
        <img src="img/passport.jpg" alt="ID Template" class="passport-bg">

        <!-- Overlay text fields -->
        <div class="passport-text mid">001234</div>
        <div class="passport-text firstname">Juan</div>
        <div class="passport-text lastname">Delacruz</div>
        <div class="passport-text middleinitial">S.</div>
        <div class="passport-text extension">Jr.</div>
        <div class="passport-text room">Room 305</div>
        <div class="passport-text seat">Seat 12</div>
    </div>
</div>

<script>
    const modal = document.getElementById("passportModal");
    const openBtn = document.getElementById("openPassport");
    const closeBtn = document.querySelector(".modal .close");
    const downloadBtn = document.getElementById("downloadPassport");
    const passportCard = document.getElementById("passportCard");

    // Open modal
    openBtn.onclick = function(e) {
        e.preventDefault();
        modal.style.display = "flex";
    }

    // Close modal
    closeBtn.onclick = function() {
        modal.style.display = "none";
    }

    // Close when clicking outside
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Download as image
    downloadBtn.onclick = function() {
        html2canvas(passportCard, { scale: 3 }).then(canvas => {
            const link = document.createElement("a");
            link.download = "passport.png";
            link.href = canvas.toDataURL("image/png");
            link.click();
        });
    }
</script>
