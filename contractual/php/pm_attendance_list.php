<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../connection/conn.php');

if (!isset($_SESSION['contractual_id'])) {
    header("Location: ../../contractual-login.php");
    exit;
}

// =========================================================
// Contractual's assigned room
// =========================================================
$contractual_room = isset($_SESSION['room_number']) ? trim($_SESSION['room_number']) : null;

// =========================================================
// Handle PHP form submission (manual confirm)
// =========================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = trim($_POST['username']);

    if (!empty($username)) {
        try {
            // Verify member exists
            $check = $pdo->prepare("SELECT * FROM members WHERE username = ?");
            $check->execute([$username]);
            $member = $check->fetch(PDO::FETCH_ASSOC);

            if ($member) {
                // Check if already scanned
                $checkAttendance = $pdo->prepare("SELECT attendance FROM pm_attendance WHERE username = ?");
                $checkAttendance->execute([$username]);
                $attendanceRecord = $checkAttendance->fetch(PDO::FETCH_ASSOC);

                if ($attendanceRecord && $attendanceRecord['attendance']) {
                    echo "<script>alert('Member already scanned for PM.');</script>";
                } else {
                    // Insert or update attendance
                    $sql = "
                        INSERT INTO pm_attendance (username, attendance)
                        VALUES (:username, TRUE)
                        ON DUPLICATE KEY UPDATE attendance = TRUE, timestamp = CURRENT_TIMESTAMP
                    ";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['username' => $username]);

                    echo "<script>alert('PM Attendance recorded successfully.');</script>";
                }
            } else {
                echo "<script>alert('Member not found.');</script>";
            }
        } catch (Exception $e) {
            echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
        }
    } else {
        echo "<script>alert('Username missing.');</script>";
    }
}

// =========================================================
// Prepare filter by room
// =========================================================
$whereClause = "";
$params = [];

if ($contractual_room !== null && $contractual_room !== '') {
    $whereClause = "WHERE m.room_number = :room_number";
    $params['room_number'] = $contractual_room;
}

// =========================================================
// Fetch members
// =========================================================
$sql = "
    SELECT 
        m.id,
        m.examination_id,
        m.username,
        m.firstname,
        m.middlename,
        m.lastname,
        m.training_institution,
        m.room_number,
        m.seat_number,
        m.prc_number,
        IFNULL(a.attendance, 0) AS pm_attendance
    FROM members m
    LEFT JOIN pm_attendance a ON m.username = a.username
    $whereClause
    ORDER BY m.room_number, m.seat_number
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<link rel="stylesheet" href="css/modal.css">
<div class="search-container">
    <input type="text" id="searchInput" placeholder="Search members..." onkeyup="filterTable()">
    <button id="searchButton" onclick="filterTable()">Search</button>
    <button id="scanButton" onclick="startScan()">Scan</button>
</div>

<!-- Hidden form -->
<form id="attendanceForm" method="POST" action="">
    <input type="hidden" name="username" id="usernameInput">
</form>

<!-- Camera Modal -->
<div id="cameraModal" class="camera-modal" aria-hidden="true" role="dialog" aria-label="QR scanner">
    <div class="camera-modal__backdrop" data-close></div>
    <div class="camera-modal__panel" role="document">
        <header class="camera-modal__header">
            <h2 class="camera-modal__title">Scan QR Code (PM)</h2>
            <button class="camera-modal__close" id="cameraModalClose" aria-label="Close scanner">✕</button>
        </header>
        <div class="camera-modal__body">
            <div id="reader" class="camera-modal__reader"></div>
            <div class="camera-modal__footer">
                <button class="btn camera-modal__stop" id="cameraModalStop">Stop Scanner</button>
            </div>
        </div>
    </div>
</div>

<!-- Member Details Confirmation Modal -->
<div class="member-modal" id="memberModal" aria-hidden="true">
  <div class="member-modal__backdrop"></div>
  <div class="member-modal__panel">
    <div class="member-modal__header">
      <h3 class="member-modal__title">Confirm Member Details (PM)</h3>
      <button class="member-modal__close" id="closeMemberModal">&times;</button>
    </div>
    <div class="member-modal__body">
      <table class="attendance-table">
        <tr><th>ID</th><td id="modalID">—</td></tr>
        <tr><th>Examination ID</th><td id="modalExamID">—</td></tr>
        <tr><th>Username</th><td id="modalUsername">—</td></tr>
        <tr><th>Full Name</th><td id="modalFullName">—</td></tr>
        <tr><th>Training Institution</th><td id="modalInstitution">—</td></tr>
        <tr><th>Room Number</th><td id="modalRoom">—</td></tr>
        <tr><th>Seat Number</th><td id="modalSeat">—</td></tr>
        <tr><th>PRC Number</th><td id="modalPRC">—</td></tr>
      </table>
    </div>
    <div class="member-modal__footer">
      <button type="button" id="confirmAttendanceBtn" class="btn-confirm">Confirm Attendance</button>
    </div>
  </div>
</div>

<!-- Table -->
<div class="table-responsive">
    <table class="attendance-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Examination ID</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Training Institution</th>
                <th>Room Number</th>
                <th>Seat Number</th>
                <th>PRC Number</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($members)): ?>
                <?php foreach ($members as $record): ?>
                    <?php
                        $fullName = htmlspecialchars(trim(
                            $record['lastname'] . ', ' . $record['firstname'] . ' ' . $record['middlename']
                        ));
                    ?>
                    <tr data-id="<?= htmlspecialchars($record['id']) ?>"
                        data-username="<?= htmlspecialchars($record['username']) ?>"
                        data-exam="<?= htmlspecialchars($record['examination_id']) ?>"
                        data-name="<?= $fullName ?>"
                        data-inst="<?= htmlspecialchars($record['training_institution']) ?>"
                        data-room="<?= htmlspecialchars($record['room_number']) ?>"
                        data-seat="<?= htmlspecialchars($record['seat_number']) ?>"
                        data-prc="<?= htmlspecialchars($record['prc_number']) ?>">
                        <td><?= htmlspecialchars($record['id']) ?></td>
                        <td><?= htmlspecialchars($record['examination_id']) ?></td>
                        <td><?= htmlspecialchars($record['username']) ?></td>
                        <td><?= $fullName ?: '-' ?></td>
                        <td><?= htmlspecialchars($record['training_institution']) ?></td>
                        <td><?= htmlspecialchars($record['room_number']) ?></td>
                        <td><?= htmlspecialchars($record['seat_number']) ?></td>
                        <td><?= htmlspecialchars($record['prc_number']) ?></td>
                        <td>
                            <div class="status-icon" style="background-color: <?= $record['pm_attendance'] ? 'green' : 'transparent' ?>;">
                                <span class="checkmark">✔</span>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="9">No member records found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- HTML5 QR Code library -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
let html5QrCode;

function filterTable() {
  const filter = document.getElementById("searchInput").value.toLowerCase();
  document.querySelectorAll(".attendance-table tbody tr").forEach(row => {
    const match = Array.from(row.children).some(td => td.textContent.toLowerCase().includes(filter));
    row.style.display = match ? "" : "none";
  });
}

function openCameraModal() {
  document.getElementById("cameraModal").classList.add("is-open");
}
function closeCameraModal() {
  document.getElementById("cameraModal").classList.remove("is-open");
}
function startScan() {
  openCameraModal();
  if (!html5QrCode) html5QrCode = new Html5Qrcode("reader");
  html5QrCode.start(
    { facingMode: "environment" },
    { fps: 10, qrbox: 250 },
    (decodedText) => {
      html5QrCode.stop().then(() => {
        closeCameraModal();
        extractAndShowMember(decodedText);
      }).catch(err => console.error("Stop failed:", err));
    },
    () => {}
  ).catch(err => alert("Camera access failed: " + err));
}
function stopScan() {
  closeCameraModal();
  if (html5QrCode) html5QrCode.stop().catch(err => console.error(err));
}

function extractAndShowMember(qrValue) {
  try {
    const parsedUrl = new URL(qrValue);
    const username = parsedUrl.searchParams.get('entry.1181033758');
    if (!username) {
      alert("QR code missing username field.");
      return;
    }

    const row = [...document.querySelectorAll("tbody tr")].find(r => r.dataset.username === username);
    if (!row) {
      alert("Member not found in table.");
      return;
    }

    document.getElementById("modalID").textContent = row.dataset.id;
    document.getElementById("modalExamID").textContent = row.dataset.exam;
    document.getElementById("modalUsername").textContent = row.dataset.username;
    document.getElementById("modalFullName").textContent = row.dataset.name;
    document.getElementById("modalInstitution").textContent = row.dataset.inst;
    document.getElementById("modalRoom").textContent = row.dataset.room;
    document.getElementById("modalSeat").textContent = row.dataset.seat;
    document.getElementById("modalPRC").textContent = row.dataset.prc;

    document.getElementById("usernameInput").value = username;
    document.getElementById("memberModal").classList.add("is-open");
  } catch (e) {
    alert("Invalid QR code format.");
  }
}

document.getElementById("confirmAttendanceBtn").addEventListener("click", () => {
  const confirmAction = confirm("Confirm PM attendance for this member?");
  if (confirmAction) {
    document.getElementById("attendanceForm").submit();
    document.getElementById("memberModal").classList.remove("is-open");
  }
});

document.getElementById("closeMemberModal").addEventListener("click", () => {
  document.getElementById("memberModal").classList.remove("is-open");
});
document.getElementById("cameraModalClose").addEventListener("click", stopScan);
document.getElementById("cameraModalStop").addEventListener("click", stopScan);
document.addEventListener("keydown", e => { if (e.key === "Escape") stopScan(); });
</script>
