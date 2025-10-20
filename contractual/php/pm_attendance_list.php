<?php
// ===============================
// Members List + QR Scanner Page (PM Attendance)
// ===============================

include('../connection/conn.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['contractual_id'])) {
    header("Location: ../../contractual-login.php"); // redirect to contractual login page
    exit;
}
// =========================================================
// Contractual's assigned room (from session)
// If this is set, the list will be filtered to this room only.
// If not set, the page will show all members (you can change that behavior if desired).
$contractual_room = isset($_SESSION['room_number']) ? trim($_SESSION['room_number']) : null;

// =========================================================
// Handle PHP form submission (non-AJAX)
// =========================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = trim($_POST['username']);

    if (!empty($username)) {
        try {
            // Verify member exists
            $check = $pdo->prepare("SELECT username FROM members WHERE username = ?");
            $check->execute([$username]);
            $member = $check->fetch(PDO::FETCH_ASSOC);

            if ($member) {
                // Check if already scanned
                $checkAttendance = $pdo->prepare("SELECT attendance FROM pm_attendance WHERE username = ?");
                $checkAttendance->execute([$username]);
                $attendanceRecord = $checkAttendance->fetch(PDO::FETCH_ASSOC);

                if ($attendanceRecord && $attendanceRecord['attendance']) {
                    echo "<script>alert('Member already scanned.');</script>";
                } else {
                    // Insert or update PM attendance record
                    $sql = "
                        INSERT INTO pm_attendance (username, attendance)
                        VALUES (:username, TRUE)
                        ON DUPLICATE KEY UPDATE attendance = TRUE, timestamp = CURRENT_TIMESTAMP
                    ";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['username' => $username]);

                    echo "<script>alert('Attendance recorded successfully.');</script>";
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
// Prepare WHERE clause: if contractual_room exists, filter by it.
// No dropdown or manual filter — automatic only for logged-in contractual.
$whereClause = "";
$params = [];

if ($contractual_room !== null && $contractual_room !== '') {
    $whereClause = "WHERE m.room_number = :room_number";
    $params['room_number'] = $contractual_room;
}

// =========================================================
// Fetch all members (filtered by contractual's room if present)
// =========================================================
$sql = "
    SELECT m.id, m.examination_id, m.username, m.firstname, m.middlename, m.lastname, 
           m.seat_number, m.room_number,
           IFNULL(p.attendance, 0) AS pm_attendance
    FROM members m
    LEFT JOIN pm_attendance p ON m.username = p.username
    $whereClause
    ORDER BY m.room_number, m.seat_number
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="search-container">
    <input type="text" id="searchInput" placeholder="Search members..." onkeyup="filterTable()">
    <button id="searchButton" onclick="filterTable()">Search</button>
    <button id="scanButton" onclick="startScan()">Scan</button>
</div>

<!-- Hidden form to submit username -->
<form id="attendanceForm" method="POST" action="">
    <input type="hidden" name="username" id="usernameInput">
</form>

<!-- Camera modal for QR scanning -->
<div id="cameraModal" class="camera-modal" aria-hidden="true" role="dialog" aria-label="QR scanner">
    <div class="camera-modal__backdrop" data-close></div>

    <div class="camera-modal__panel" role="document">
        <header class="camera-modal__header">
            <h2 class="camera-modal__title">Scan QR Code</h2>
            <button class="camera-modal__close" id="cameraModalClose" aria-label="Close scanner" title="Close (Esc)" data-close>✕</button>
        </header>

        <div class="camera-modal__body">
            <div id="reader" class="camera-modal__reader"></div>
            <div class="camera-modal__footer">
                <button class="btn camera-modal__stop" id="cameraModalStop">Stop Scanner</button>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="attendance-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Examination ID</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Room Number</th>
                <th>Seat Number</th>
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
                    <tr data-id="<?= htmlspecialchars($record['id']) ?>">
                        <td><?= htmlspecialchars($record['id']) ?></td>
                        <td><?= htmlspecialchars($record['examination_id']) ?></td>
                        <td><?= htmlspecialchars($record['username']) ?></td>
                        <td><?= $fullName ?: '-' ?></td>
                        <td><?= htmlspecialchars($record['room_number']) ?></td>
                        <td><?= htmlspecialchars($record['seat_number']) ?></td>
                        <td>
                            <div class="status-icon" style="background-color: <?= $record['pm_attendance'] ? 'green' : 'transparent' ?>;">
                                <span class="checkmark">✔</span>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">No member records found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- HTML5 QR Code library -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
// ===============================
// SEARCH FILTER
// ===============================
function filterTable() {
    const input = document.getElementById("searchInput");
    const filter = input.value.toLowerCase();
    const rows = document.querySelectorAll(".attendance-table tbody tr");

    rows.forEach(row => {
        const cells = row.querySelectorAll("td");
        const match = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(filter));
        row.style.display = match ? "" : "none";
    });
}

// ===============================
// QR SCANNER + MODAL
// ===============================
let html5QrCode;

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
                extractAndSubmitUsername(decodedText);
            }).catch(err => console.error("Stop failed:", err));
        },
        () => {} // ignore scan errors
    ).catch(err => alert("Camera access failed: " + err));
}

function stopScan() {
    closeCameraModal();
    if (html5QrCode) {
        html5QrCode.stop().catch(err => console.error("Stop error:", err));
    }
}

// ===============================
// Extract username and submit form
// ===============================
function extractAndSubmitUsername(qrValue) {
    try {
        const parsedUrl = new URL(qrValue);
        const username = parsedUrl.searchParams.get('entry.1181033758'); // username from QR

        if (!username) {
            alert("QR code does not contain a username field.");
            return;
        }

        // Set username and submit form
        document.getElementById("usernameInput").value = username;
        document.getElementById("attendanceForm").submit();

    } catch (e) {
        alert("Invalid QR code format.");
        console.error(e);
    }
}

document.getElementById("cameraModalClose").addEventListener("click", stopScan);
document.getElementById("cameraModalStop").addEventListener("click", stopScan);
document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") stopScan();
});
</script>
