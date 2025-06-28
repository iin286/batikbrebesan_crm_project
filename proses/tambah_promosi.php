<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $diskon = $_POST['diskon'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $deskripsi = $_POST['deskripsi'];

    $stmt = $conn->prepare("INSERT INTO promosi (judul, diskon, tanggal_mulai, tanggal_selesai, deskripsi) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisss", $judul, $diskon, $tanggal_mulai, $tanggal_selesai, $deskripsi);
    $stmt->execute();

    header("Location: ../pages/promosi.php");
    exit;
}
?>
