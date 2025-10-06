<?php
include('../../connection/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = trim($_POST['title']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);
    $location = trim($_POST['location']);

    $sql = "UPDATE examinations 
            SET title = :title, date = :date, time = :time, location = :location
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'title' => $title,
        'date' => $date,
        'time' => $time,
        'location' => $location,
        'id' => $id
    ]);

    header("Location: ../examinations.php");
    exit;
}
?>
